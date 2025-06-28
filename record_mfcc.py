import sounddevice as sd
import librosa
import librosa.display
import numpy as np
import matplotlib.pyplot as plt

# Parameters
fs = 16000       # Sample rate (Hz)
duration = 3     # Duration of recording (seconds)

# Record audio
print("🎤 Recording...")
audio = sd.rec(int(duration * fs), samplerate=fs, channels=1)
sd.wait()
print("✅ Done recording")

# Flatten to 1D array (mono)
audio = audio.flatten()

# Extract MFCCs
mfcc = librosa.feature.mfcc(y=audio, sr=fs, n_mfcc=13)
print("MFCC shape:", mfcc.shape)

# Save MFCC features to files
np.save('mfcc_features.npy', mfcc)                    # NumPy binary format
np.savetxt('mfcc_features.csv', mfcc, delimiter=',') # CSV text format
print("✅ MFCC features saved as 'mfcc_features.npy' and 'mfcc_features.csv'.")

# Visualize MFCC
plt.figure(figsize=(10, 4))
librosa.display.specshow(mfcc, x_axis='time')
plt.colorbar(format='%+2.0f dB')
plt.title('MFCC')
plt.tight_layout()
plt.show()
