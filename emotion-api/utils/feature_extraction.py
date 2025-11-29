import numpy as np
import librosa

def extract_features(audio, sr):
    """
    Ekstraksi fitur audio untuk CNN
    """
    mfccs = librosa.feature.mfcc(y=audio, sr=sr, n_mfcc=40)
    mel = librosa.feature.melspectrogram(y=audio, sr=sr)
    chroma = librosa.feature.chroma_stft(y=audio, sr=sr)
    contrast = librosa.feature.spectral_contrast(y=audio, sr=sr)
    tonnetz = librosa.feature.tonnetz(y=librosa.effects.harmonic(audio), sr=sr)

    features = np.hstack([
        np.mean(mfccs, axis=1),
        np.mean(mel, axis=1),
        np.mean(chroma, axis=1),
        np.mean(contrast, axis=1),
        np.mean(tonnetz, axis=1)
    ])

    return features
