import React from "react";
import ReactDOM from "react-dom/client";
import Waveform from "./components/Waveform";
import audioFile from "../audio/test.mp3";

ReactDOM.createRoot(document.getElementById("app")).render(
  <React.StrictMode>
    <div style={{ padding: "20px" }}>
      <h1>Test Waveform</h1>
      <Waveform audioFile={audioFile} />
    </div>
  </React.StrictMode>
);
