from fastapi import FastAPI, UploadFile, File, HTTPException, Form
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
import librosa
import numpy as np
import pandas as pd
import math
import io
import base64
import matplotlib
matplotlib.use("Agg")
import matplotlib.pyplot as plt
from tensorflow.keras.models import load_model
import os

# ================== APP & CORS ==================
app = FastAPI(title="Speech Emotion API", version="2.0.0")

origins = [
    "http://127.0.0.1:8000",
    "http://localhost:8000",
    "http://127.0.0.1",
    "http://localhost",
    "http://localhost:5173",
    "https://www.suaraku.fun"
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ================== KONFIGURASI ==================
EMOTION_MODEL_PATH = "model/model_skenario_0.h5"
SUKU_MODEL_PATH    = "model/model_suku_skenario0.h5"
SAMPLING_RATE = 22050
FRAME_DURATION = 2.0
HOP_DURATION = 1.0

TARGET_LABELS = ['Happy', 'Sad', 'Angry', 'Surprised', 'Neutral']
SUKU_LABELS = ['Jawa', 'Sunda', 'Batak', 'Minang', 'Betawi']

SCENARIO_PARAMS = (32, 0.001, 0.1, 13, True, True, True)

# ================== LOAD MODEL ==================
emotion_model = None
suku_model = None

try:
    emotion_model = load_model(EMOTION_MODEL_PATH)
    print("[INIT] Emotion model loaded.")
except Exception as e:
    print(f"[INIT] Failed to load emotion model: {e}")

try:
    suku_model = load_model(SUKU_MODEL_PATH)
    print("[INIT] Suku model loaded.")
except Exception as e:
    print(f"[INIT] Failed to load suku model: {e}")

# ================== UTIL ==================
def extract_features_from_audio_with_scenario(y, sr, scenario_params):
    n_neuron, lr, dr, n_mfcc, use_zcr, use_pitch, use_spec_centroid = scenario_params
    feats = []

    mfccs = librosa.feature.mfcc(y=y, sr=sr, n_mfcc=n_mfcc)
    feats.extend(np.mean(mfccs, axis=1))

    if use_zcr:
        zcr = np.mean(librosa.feature.zero_crossing_rate(y))
        feats.append(zcr)

    if use_pitch:
        pitches, magnitudes = librosa.piptrack(y=y, sr=sr)
        pitch_values = pitches[magnitudes > np.median(magnitudes)]
        feats.append(np.mean(pitch_values) if len(pitch_values) > 0 else 0)

    if use_spec_centroid:
        spec_centroid = np.mean(librosa.feature.spectral_centroid(y=y, sr=sr))
        feats.append(spec_centroid)

    return np.array(feats)


def split_audio_to_frames(y, sr, frame_duration=2.0, hop_duration=1.0):
    frame_len = int(frame_duration * sr)
    hop_len = int(hop_duration * sr)
    frames, starts = [], []
    for start in range(0, len(y) - frame_len + 1, hop_len):
        frames.append(y[start:start + frame_len])
        starts.append(start)
    return frames, starts


def predict_frames(frames, sr, model, scenario_params, frame_duration):
    X_frames = []
    for f in frames:
        if len(f) < int(sr * frame_duration):
            pad_len = int(sr * frame_duration) - len(f)
            f = np.concatenate([f, np.zeros(pad_len)])
        feats = extract_features_from_audio_with_scenario(f, sr, scenario_params)
        X_frames.append(feats)

    X = np.array(X_frames)
    X_resh = np.expand_dims(X, axis=-1)
    preds = model.predict(X_resh, verbose=0)
    pred_indices = np.argmax(preds, axis=1)
    pred_labels = [TARGET_LABELS[i] for i in pred_indices]
    pred_probs = np.max(preds, axis=1)
    return pred_labels, pred_probs

def predict_suku_frames(frames, sr, model, scenario_params, frame_duration):
    X_frames = []
    for f in frames:
        if len(f) < int(sr * frame_duration):
            pad_len = int(sr * frame_duration) - len(f)
            f = np.concatenate([f, np.zeros(pad_len)])
        feats = extract_features_from_audio_with_scenario(f, sr, scenario_params)
        X_frames.append(feats)

    X = np.array(X_frames)
    X_resh = np.expand_dims(X, axis=-1)

    preds = model.predict(X_resh, verbose=0)
    pred_indices = np.argmax(preds, axis=1)
    pred_labels = [SUKU_LABELS[i] for i in pred_indices]
    pred_probs = np.max(preds, axis=1)

    return pred_labels, pred_probs

def fig_to_base64(fig):
    buf = io.BytesIO()
    fig.savefig(buf, format="png", bbox_inches="tight")
    buf.seek(0)
    img_bytes = buf.getvalue()
    base64_str = base64.b64encode(img_bytes).decode("utf-8")
    plt.close(fig)
    return base64_str

# ================== ENDPOINT ==================
@app.get("/health")
def health():
    return {
        "status": "ok",
        "emotion_model_loaded": emotion_model is not None,
        "suku_model_loaded": suku_model is not None
    }

@app.post("/analyze-audio")
async def analyze_audio(
    file: UploadFile = File(...),
    sampling_rate: int = SAMPLING_RATE,
    frame_duration: float = FRAME_DURATION,
    hop_duration: float = HOP_DURATION,
):
    if emotion_model is None:
        raise HTTPException(status_code=500, detail="Model belum termuat di server.")

    # Load audio file
    try:
        audio_bytes = await file.read()
    except Exception:
        raise HTTPException(status_code=400, detail="Gagal membaca file audio.")

    try:
        y, sr = librosa.load(io.BytesIO(audio_bytes), sr=sampling_rate, mono=True)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Gagal load audio: {e}")

    frames, starts = split_audio_to_frames(y, sr, frame_duration, hop_duration)

    # Predict emotion
    pred_labels, pred_probs = predict_frames(frames, sr, emotion_model, SCENARIO_PARAMS, frame_duration)
    if suku_model is not None:
        suku_labels, suku_probs = predict_suku_frames(frames, sr, suku_model, SCENARIO_PARAMS, frame_duration)
    else:
        suku_labels = ["Unknown"] * len(frames)
        suku_probs = [0] * len(frames)

    # Build dataframe
    df_results = pd.DataFrame({
        "start_time_s": [s / sr for s in starts],
        "emotion_label": pred_labels,
        "emotion_prob": pred_probs,
        "suku_label": suku_labels,
        "suku_prob": suku_probs,
    })

    label_counts = df_results["emotion_label"].value_counts().sort_index()
    total_frames = len(df_results)

    dominant_emotion = label_counts.idxmax()
    dominant_pct = (label_counts[dominant_emotion] / total_frames * 100) if total_frames > 0 else 0.0

    avg_confidence = float(np.mean(pred_probs))
    std_confidence = float(np.std(pred_probs))

    suku_counts = df_results["suku_label"].value_counts().sort_index()
    dominant_suku = suku_counts.idxmax()
    dominant_suku_pct = (suku_counts[dominant_suku] / total_frames * 100) if total_frames > 0 else 0.0

    # Bar Chart (5 Emotions)
    emotion_order = TARGET_LABELS
    fig1, ax1 = plt.subplots(figsize=(6, 3.5))
    ax1.bar(
        emotion_order,
        [int(label_counts.get(lbl, 0)) for lbl in emotion_order]
    )
    ax1.set_title("Distribusi Emosi per Frame")
    ax1.set_xlabel("Emosi")
    ax1.set_ylabel("Jumlah Frame")
    ax1.grid(axis='y', linestyle='--', alpha=0.5)
    emotion_bar_b64 = fig_to_base64(fig1)

    # Pie Chart Emotions
    vals = [int(label_counts.get(lbl, 0)) for lbl in emotion_order]
    fig2, ax2 = plt.subplots(figsize=(4, 4))
    ax2.pie(vals, labels=emotion_order, autopct='%1.1f%%', startangle=90)
    ax2.set_title("Proporsi Emosi")
    emotion_pie_b64 = fig_to_base64(fig2)

    # Response
    response_data = {
        "audio_duration_s": round(len(y) / sr, 2),
        "total_frames": total_frames,
        "frames": df_results.to_dict(orient="records"),

        "distribution_by_emotion": {
            lbl: {
                "count": int(label_counts.get(lbl, 0)),
                "percent": round((label_counts.get(lbl, 0) / total_frames) * 100, 4)
                if total_frames > 0 else 0.0
            }
            for lbl in TARGET_LABELS
        },

        "dominant_emotion": dominant_emotion,
        "dominant_emotion_percent": round(dominant_pct, 4),\
        
        "distribution_by_suku": {
            lbl: {
                "count": int(suku_counts.get(lbl, 0)),
                "percent": round((suku_counts.get(lbl, 0) / total_frames) * 100, 4)
                if total_frames > 0 else 0.0
            }
            for lbl in SUKU_LABELS
        },

        "dominant_suku": dominant_suku,
        "dominant_suku_percent": round(dominant_suku_pct, 4),

        "evaluation": {
            "avg_confidence": round(avg_confidence * 100, 2),
            "std_confidence": round(std_confidence * 100, 2),
        },

        "charts": {
            "emotion_bar_png_base64": emotion_bar_b64,
            "emotion_pie_png_base64": emotion_pie_b64
        }
    }

    return JSONResponse(content=response_data)


# ========== LEGACY ENDPOINT (Masih bekerja tetapi tanpa kategori) ==========
@app.post("/predict/")
async def predict_legacy(file: UploadFile = File(...)):
    """Mengembalikan persentase 5 emosi, bukan kategori."""
    res = await analyze_audio(file)
    import json
    parsed = json.loads(res.body)

    dist = parsed.get("distribution_by_emotion", {})

    emotion_percent = {}
    for lbl in TARGET_LABELS:
        p = dist.get(lbl, {}).get("percent", 0.0) / 100.0
        emotion_percent[lbl] = p

    dominant_emotion = parsed.get("dominant_emotion", None)

    return {
        "emotion": emotion_percent,
        "dominant": dominant_emotion
    }


# MAIN
if __name__ == "__main__":
    import uvicorn
    uvicorn.run("app:app", host="127.0.0.1", port=8002, reload=True)