import numpy as np
import librosa
import pandas as pd
from tensorflow.keras.models import load_model

# ==========================
# KONFIGURASI LABEL & AUDIO
# ==========================

TARGET_LABELS = ['Happy', 'Sad', 'Angry', 'Surprised', 'Neutral']

SCENARIO_PARAMS = (32, 0.001, 0.1, 13, True, True, True)

SAMPLING_RATE = 22050
FRAME_DURATION = 2.0
HOP_DURATION = 1.0


# ==========================
# FEATURE EXTRACTION
# ==========================

def extract_features_from_audio_with_scenario(y, sr, params):
    """
    Ekstraksi fitur audio berdasarkan skenario:
    MFCC + ZCR + Pitch + Spectral Centroid.
    """
    n_neuron, lr, dr, n_mfcc, use_zcr, use_pitch, use_spec_centroid = params
    feats = []

    # MFCC (mean per 13 koefisien)
    mfccs = librosa.feature.mfcc(y=y, sr=sr, n_mfcc=n_mfcc)
    feats.extend(np.mean(mfccs, axis=1))

    # Zero Crossing Rate (ZCR)
    if use_zcr:
        zcr = np.mean(librosa.feature.zero_crossing_rate(y))
        feats.append(zcr)

    # Pitch Extraction
    if use_pitch:
        pitches, mags = librosa.piptrack(y=y, sr=sr)
        pitch_vals = pitches[mags > np.median(mags)]
        feats.append(np.mean(pitch_vals) if len(pitch_vals) > 0 else 0)

    # Spectral Centroid
    if use_spec_centroid:
        centroid = np.mean(librosa.feature.spectral_centroid(y=y, sr=sr))
        feats.append(centroid)

    return np.array(feats)


# ==========================
# SPLIT AUDIO FRAME
# ==========================

def split_audio_to_frames(y, sr, frame_duration=FRAME_DURATION, hop_duration=HOP_DURATION):
    """
    Membagi audio menjadi beberapa frame (overlap).
    """
    frame_len = int(frame_duration * sr)
    hop_len = int(hop_duration * sr)

    frames, starts = [], []

    for start in range(0, len(y) - frame_len + 1, hop_len):
        frames.append(y[start:start + frame_len])
        starts.append(start)

    return frames, starts


# ==========================
# PREDIKSI EMOSI PER FRAME
# ==========================

def predict_audio(model, y, sr):
    """
    Memproses audio penuh → split → ekstraksi fitur → prediksi per frame.

    Output:
        df_results → DataFrame hasil per frame
        summary    → Statistik lengkap
    """

    frames, starts = split_audio_to_frames(y, sr, FRAME_DURATION, HOP_DURATION)

    X_frames = []
    for frame in frames:
        # Padding frame pendek
        if len(frame) < int(sr * FRAME_DURATION):
            pad_len = int(sr * FRAME_DURATION) - len(frame)
            frame = np.concatenate([frame, np.zeros(pad_len)])

        feats = extract_features_from_audio_with_scenario(frame, sr, SCENARIO_PARAMS)
        X_frames.append(feats)

    X = np.array(X_frames)
    X = np.expand_dims(X, axis=-1)

    # Predict
    preds = model.predict(X, verbose=0)
    pred_indices = np.argmax(preds, axis=1)
    pred_labels = [TARGET_LABELS[i] for i in pred_indices]
    pred_probs = np.max(preds, axis=1)

    # Build DataFrame
    df_results = pd.DataFrame({
        "start_time_s": [s / sr for s in starts],
        "label": pred_labels,
        "prob": pred_probs
    })

    # ===================
    #   STATISTIK EMOSI
    # ===================
    label_counts = df_results["label"].value_counts().sort_index()
    total_frames = len(df_results)

    dominant_emotion = label_counts.idxmax()
    dominant_pct = (label_counts[dominant_emotion] / total_frames * 100)

    summary = {
        "total_frames": total_frames,
        "dominant_emotion": dominant_emotion,
        "dominant_percentage": round(dominant_pct, 2),
        "avg_confidence": round(float(np.mean(pred_probs)) * 100, 2)
    }

    return df_results, summary


# ==========================
# LOAD MODEL API
# ==========================

def load_emotion_model(model_path):
    """
    Utility untuk load model Keras.
    """
    try:
        model = load_model(model_path)
        print(f"[model_utils] Model loaded: {model_path}")
        return model
    except Exception as e:
        print(f"[model_utils] Failed loading model: {e}")
        return None
