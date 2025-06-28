import sounddevice as sd
import librosa
import numpy as np

fs = 16000  # sample rate
duration = 3  # seconds

print("🎤 Recording...")
audio = sd.rec(int(duration * fs), samplerate=fs, channels=1)
sd.wait()
print("✅ Done recording")

# Extract MFCCs
mfcc = librosa.feature.mfcc(y=audio.flatten(), sr=fs, n_mfcc=13)
print("MFCC shape:", mfcc.shape)
print(mfcc)
