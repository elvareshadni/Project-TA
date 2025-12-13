import React, { useEffect, useRef } from "react";
import WaveSurfer from "wavesurfer.js";

const Waveform = ({ audioFile }) => {
    const waveformRef = useRef(null);
    const wavesurferRef = useRef(null);

    useEffect(() => {
        if (!waveformRef.current) return;

        wavesurferRef.current = WaveSurfer.create({
            container: waveformRef.current,
            waveColor: "#4D7CFE",
            progressColor: "#1D4ED8",
            height: 100,
        });

        wavesurferRef.current.load(audioFile);

        return () => wavesurferRef.current.destroy();
    }, [audioFile]);

    return (
        <div>
            <div ref={waveformRef}></div>
            <button onClick={() => wavesurferRef.current.playPause()}>
                Play / Pause
            </button>
        </div>
    );
};

export default Waveform;
