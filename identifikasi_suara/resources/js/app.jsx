import React from 'react';
import { createRoot } from 'react-dom/client';
import { FileContextProvider } from '../contexts/fileContext';
import AudioWaveform from "../components/AudioWaveform";

const waveformRoot = document.getElementById('waveform-root');
if (waveformRoot) {
  createRoot(waveformRoot).render(
    <React.StrictMode>
      <FileContextProvider>
        <AudioWaveform />
      </FileContextProvider>
    </React.StrictMode>
  );
}
