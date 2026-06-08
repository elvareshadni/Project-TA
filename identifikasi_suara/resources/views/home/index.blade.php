<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUARA KU - Identifikasi Emosi Suara</title>
    <link rel="icon" href="./img/favicon.png" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- WaveSurfer.js -->
    <script src="https://unpkg.com/wavesurfer.js@7/dist/wavesurfer.min.js"></script>
    <!-- RecordRTC -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/RecordRTC/5.6.2/RecordRTC.min.js"></script>
    <!-- jsPDF & AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <div class="logo">
        <a href="{{ route('home.dashboard') }}" style="display: flex; align-items: center; text-decoration: none; gap: 10px;">
            <img src="{{ asset('img/logo-suarakuu.png') }}"
             alt="Logo Suaraku"
             style="width:125px; height:auto;">
        </a>
    </div>
    <nav style="display: flex; align-items: center; ">
        <span class="username">
            Halo, <strong>{{ session('username') }}</strong>
        </span>
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:inline; margin:0;">
            @csrf
            <button type="button" class="btn-logout" id="btnLogout" style="background:none; border:none; color:#ffffff; cursor:pointer; display: flex; align-items: center;">
                <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
            </button>
        </form>
    </nav>
</header>

<section class="content-section">
<div class="main-wrapper">

    {{-- MAIN UNIFIED CARD --}}
    <div class="dashboard-card">
        
        {{-- LEFT COLUMN: INPUT (MIC ONLY) --}}
        <div class="mic-section">
            
            {{-- RECORD CONTENT --}}
            <div id="recordTabWrapper" style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <!-- Mic Button -->
                <button class="new-mic-btn" id="newMicBtn" onclick="toggleRecording()">
                    <i class="fa-solid fa-microphone" id="newMicIcon"></i>
                </button>
                
                <!-- Status Message -->
                <div class="mic-status-text" id="newMicStatusText">Klik untuk mulai rekam</div>
                
                <!-- Recording Canvas Visualizer -->
                <div id="recordingVisualizerContainer" style="display: none; width: 100%; max-width: 280px; height: 50px; margin: 8px auto;">
                    <canvas id="recordingVisualizer" style="width: 100%; height: 100%;"></canvas>
                </div>
                
                <!-- WaveSurfer Playback Player (Fallback playback) -->
                <div id="recordPlayerSection" class="hidden" style="width:100%; max-width:280px; display:none; margin: 10px auto;">
                    <div id="recordWaveformPlayer" style="width:100%;"></div>
                    <div class="audio-controls" style="margin-top:5px; justify-content:center; gap: 10px;">
                        <button id="btnPlayRecord" class="play-control" onclick="togglePlayRecord()">
                            <i class="fa-solid fa-play" id="iconPlayRecord"></i>
                        </button>
                        <span id="recordTimerPlayback" style="font-family:monospace; font-size:12px; color: var(--text-muted);">00:00</span>
                    </div>
                </div>
                
                <!-- Timer Display -->
                <div class="timer-text" id="recordTimer" style="display: none;">00:00 / 05:00</div>
                
                <!-- Help/Warning Text -->
                <div class="mic-help-text" id="newMicHelpText">
                    Jarak mulut &plusmn; 10 cm dari mikrofon <br>
                    <span style="display: inline-block; margin-top: 5px; font-weight: bold;">Durasi minimal perekaman {{ $minSeconds }} detik</span>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: DOMINANT RESULTS --}}
        <div class="summaries-section">
            <!-- Box 1: Emosi Terdeteksi -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <i class="fa-regular fa-face-smile" style="font-size: 14px;"></i>
                    <span>Emosi Terdeteksi</span>
                </div>
                <div class="summary-card-body" id="domEmotionBody">
                    <div class="summary-empty-state" id="domEmotionEmpty">Belum ada hasil</div>
                    <!-- Dynamic result template -->
                    <div id="domEmotionResult" style="display: none; align-items: center; gap: 16px;">
                        <img src="./img/neutral.png" class="summary-avatar" id="res_emojiIcon" alt="emoji">
                        <div class="summary-details">
                            <div class="summary-name" id="res_mainEmotion">-</div>
                            <div class="summary-score" style="color: var(--primary-color); font-weight:600; font-size:13px;">Skor emosi dominan: <span id="res_mainPercent">-</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Box 2: Suku Terdeteksi -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <i class="fa-regular fa-user" style="font-size: 14px;"></i>
                    <span>Suku Terdeteksi</span>
                </div>
                <div class="summary-card-body" id="domSukuBody">
                    <div class="summary-empty-state" id="domSukuEmpty">Belum ada hasil</div>
                    <!-- Dynamic result template -->
                    <div id="domSukuResult" style="display: none; align-items: center; gap: 16px;">
                        <img src="./img/jawa.png" class="summary-avatar" id="res_sukuEmojiIcon" alt="avatar">
                        <div class="summary-details">
                            <div class="summary-name" id="res_sukuDominant">-</div>
                            <div class="summary-score" style="color: var(--primary-color); font-weight:600; font-size:13px;">Skor suku dominan: <span id="res_sukuDominantPercent">-</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- DYNAMIC BOTTOM PANEL --}}
    
    {{-- 1. PROCESSING LOADER (Hidden by default) --}}
    <div id="unifiedProcessingLoader" class="processing-loader-wrapper" style="display: none;">
        <div class="pulsing-wave-icon">
            <span></span><span></span><span></span><span></span><span></span>
        </div>
        <div class="processing-label-text">Mohon tunggu, AI kami sedang mengenali emosi Anda...</div>
    </div>

    {{-- 2. INSTRUCTIONS CARD --}}
    <div class="instructions-card" id="instructionsCard">
        <div class="instruction-step">
            <div class="step-number">1</div>
            <div class="step-text">Klik tombol mikrofon untuk mulai merekam</div>
        </div>
        <div class="instruction-step">
            <div class="step-number">2</div>
            <div class="step-text">Ucapkan kalimat dengan jelas, jarak &plusmn; 10 cm</div>
        </div>
        <div class="instruction-step">
            <div class="step-number">3</div>
            <div class="step-text">Klik lagi untuk berhenti &amp; dapatkan hasil deteksi</div>
        </div>
    </div>

    {{-- 3. RESULT DETAIL CHARTS (Hidden by default) --}}
    <div id="resultDetailsWrapper" style="display: none; width: 100%;">
        <div class="details-grid">
            <!-- Card Detail Emosi -->
            <div class="details-card">
                <div class="details-card-title">
                    <i class="fa-regular fa-face-smile" style="font-size: 16px; color: var(--primary-color);"></i>
                    <span>DETAIL EMOSI</span>
                </div>
                <div class="detail-bars-container">
                    <!-- Bahagia -->
                    <div class="progress-row">
                        <div class="progress-label">Bahagia</div>
                        <div class="progress-track-wrapper">
                            <div class="progress-track-bg">
                                <div class="progress-track-fill happy-fill" id="res_happyBar" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="progress-value" id="res_happyVal">0%</div>
                    </div>
                    <!-- Sedih -->
                    <div class="progress-row">
                        <div class="progress-label">Sedih</div>
                        <div class="progress-track-wrapper">
                            <div class="progress-track-bg">
                                <div class="progress-track-fill sad-fill" id="res_sadBar" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="progress-value" id="res_sadVal">0%</div>
                    </div>
                    <!-- Marah -->
                    <div class="progress-row">
                        <div class="progress-label">Marah</div>
                        <div class="progress-track-wrapper">
                            <div class="progress-track-bg">
                                <div class="progress-track-fill angry-fill" id="res_angryBar" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="progress-value" id="res_angryVal">0%</div>
                    </div>
                    <!-- Terkejut -->
                    <div class="progress-row">
                        <div class="progress-label">Terkejut</div>
                        <div class="progress-track-wrapper">
                            <div class="progress-track-bg">
                                <div class="progress-track-fill surprised-fill" id="res_surprisedBar" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="progress-value" id="res_surprisedVal">0%</div>
                    </div>
                    <!-- Netral -->
                    <div class="progress-row">
                        <div class="progress-label">Netral</div>
                        <div class="progress-track-wrapper">
                            <div class="progress-track-bg">
                                <div class="progress-track-fill neutral-fill" id="res_neutralBar" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="progress-value" id="res_neutralVal">0%</div>
                    </div>
                </div>
            </div>

            <!-- Card Detail Suku -->
            <div class="details-card">
                <div class="details-card-title">
                    <i class="fa-regular fa-user" style="font-size: 16px; color: var(--primary-color);"></i>
                    <span>DETAIL SUKU</span>
                </div>
                <div class="detail-bars-container" id="res_sukuBars">
                    <!-- Dynamic rendering in JS -->
                </div>
            </div>
        </div>

        {{-- FOOTER STATUS BANNER & ACTION --}}
        <div class="footer-row">
            <div>
                <button class="btn-pratinjau" onclick="previewResult()">
                    <i class="fa-solid fa-eye"></i> Pratinjau Hasil
                </button>
            </div>
            
            <div class="status-banner" id="saveSuccess" style="display: none;">
                <i class="fa-solid fa-envelope-circle-check"></i>
                <span>Hasil analisis berhasil disimpan &amp; dikirim ke email: <strong>{{ session('email') }}</strong></span>
            </div>
            
            <div class="status-banner" id="saveLoading" style="display: none; background: #f1f5f9; border-color: #cbd5e1; color: #475569;">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <span>Menyimpan data &amp; mengirim email...</span>
            </div>
            
            <div class="status-banner" id="saveError" style="display: none; background: #fee2e2; border-color: #fecaca; color: #991b1b;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span id="saveErrorMessage">Gagal menyimpan data.</span>
            </div>
        </div>
    </div>

</div>

<!-- Hidden Form for Preview -->
<form id="previewForm" action="{{ route('identifikasi.preview') }}" method="POST" target="_blank" style="display:none;">
    @csrf
    <input type="hidden" name="sumber" id="prev_sumber">
    <input type="hidden" name="file_suara" id="prev_file_suara">
    <input type="hidden" name="durasi" id="prev_durasi">
    <input type="hidden" name="hasil" id="prev_hasil">
    <input type="hidden" name="akurasi" id="prev_akurasi">
    <!-- Encode JSON for arrays -->
    <input type="hidden" name="distribution_by_emotion" id="prev_dist_emotion">
    <input type="hidden" name="distribution_by_suku" id="prev_dist_suku">
    
    <input type="hidden" name="nama" value="{{ session('username') }}">
    <input type="hidden" name="email" value="{{ session('email') }}">
    <input type="hidden" name="gender" value="{{ session('gender') }}">
    <input type="hidden" name="usia" value="{{ session('usia') }}">
</form>
</section>

<script>
const API_BASE = "http://127.0.0.1:8003";
const MAX_RECORD_SECONDS = {{ $maxSeconds ?? 300 }}; // 5 minutes default
const MIN_RECORD_SECONDS = {{ $minSeconds ?? 180 }};
const MAX_RECORD_STR = formatTime(MAX_RECORD_SECONDS);

/* ===== GLOBAL STATE ===== */
let isRecording = false;
let isPaused = false;
let audioContext;
let mediaStream;
let analyser;
let visualizerAnimationId;

// RecordRTC state
let recorder; // RecordRTC instance
let recordedWavBlob = null;

// WaveSurfer instances
let wsRecord;

// Last Results
let lastRecordResult = null;
let currentResult = null; // Store active result for preview
let currentSource = 'record'; // Always record now

/* ===== INIT WAVESURFER ===== */
document.addEventListener('DOMContentLoaded', () => {
    // Init Visualizer Record (Playback)
    wsRecord = WaveSurfer.create({
        container: '#recordWaveformPlayer',
        waveColor: '#cbd5e1',
        progressColor: '#0c56d0',
        cursorColor: '#0c56d0',
        barWidth: 2,
        barGap: 3,
        barRadius: 3,
        height: 80,
        responsive: true,
    });
    
    // Event Listeners for Record Player
    wsRecord.on('play', () => {
        document.getElementById('iconPlayRecord').className = 'fa-solid fa-pause';
    });
    wsRecord.on('pause', () => {
        document.getElementById('iconPlayRecord').className = 'fa-solid fa-play';
    });
    wsRecord.on('finish', () => {
        document.getElementById('iconPlayRecord').className = 'fa-solid fa-play';
    });
    // Timer events for Record Playback
    wsRecord.on('timeupdate', (currentTime) => {
        const duration = wsRecord.getDuration();
        document.getElementById('recordTimerPlayback').innerText = formatTime(currentTime) + " / " + formatTime(duration);
    });
    wsRecord.on('ready', (duration) => {
        document.getElementById('recordTimerPlayback').innerText = "00:00 / " + formatTime(duration);
    });

    // Logout Confirmation
    const btnLogout = document.getElementById('btnLogout');
    if(btnLogout) {
        btnLogout.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sesi ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0c56d0',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    }
});

function togglePlayRecord() {
    if(wsRecord) wsRecord.playPause();
}

/* ================= TIMER ================= */
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}
let recordingStartTime;
let timerInterval;
let pausedTime = 0;

function startTimer() {
    recordingStartTime = Date.now();
    pausedTime = 0;
    
    timerInterval = setInterval(() => {
        if(isPaused) return;

        const now = Date.now();
        const totalElapsed = Math.floor((now - recordingStartTime - pausedTime) / 1000);
        
        document.getElementById('recordTimer').textContent = `${formatTime(totalElapsed)} / ${MAX_RECORD_STR}`;

        if (totalElapsed >= MAX_RECORD_SECONDS && isRecording) {
            stopRecording(true); 
            alert('Waktu rekaman sudah mencapai batas maksimal yang diizinkan.');
        }
    }, 1000);
}
function stopTimer() { clearInterval(timerInterval); }

function stopRecording(forceStop = false) {
    toggleRecording(forceStop);
}

/* ============ REKAM → WAV (RecordRTC) ============ */
async function toggleRecording(forceStop = false) {
    const micBtn = document.getElementById('newMicBtn');
    const micIcon = document.getElementById('newMicIcon');
    const statusText = document.getElementById('newMicStatusText');
    const vizContainer = document.getElementById('recordingVisualizerContainer');
    const timerDisplay = document.getElementById('recordTimer');

    if (!isRecording) {
        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 2048;
            analyser.smoothingTimeConstant = 0.8;
            
            const source = audioContext.createMediaStreamSource(mediaStream);
            source.connect(analyser); 
            
            recorder = new RecordRTC(mediaStream, {
                type: 'audio',
                mimeType: 'audio/wav',
                recorderType: RecordRTC.StereoAudioRecorder,
                numberOfAudioChannels: 1,
                desiredSampRate: 16000 
            });

            recorder.startRecording();
            
            isRecording = true;
            isPaused = false;
            
            micBtn.classList.add('recording');
            statusText.innerText = '• Sedang merekam...';
            statusText.classList.add('recording');
            
            if(vizContainer) vizContainer.style.display = 'block';
            if(timerDisplay) {
                 timerDisplay.style.display = 'block';
                 timerDisplay.textContent = `00:00 / ${MAX_RECORD_STR}`;
            }
            
            startRealtimeVisualizer(); 
            startTimer();
        } catch (err) {
            alert('Gagal mengakses mikrofon: ' + err.message);
        }
    } else {
        const duration = Math.floor((Date.now() - recordingStartTime - pausedTime) / 1000);

        if (!forceStop && duration < MIN_RECORD_SECONDS) {
            Swal.fire({
                icon: 'warning',
                title: 'Durasi Kurang',
                text: `Durasi minimal adalah ${MIN_RECORD_SECONDS} detik.`,
            });
            return;
        }

        stopTimer();
        isRecording = false;
        
        micBtn.classList.remove('recording');
        statusText.classList.remove('recording');
        statusText.innerText = 'Klik untuk mulai rekam';
        
        if(vizContainer) vizContainer.style.display = 'none';
        if(timerDisplay) timerDisplay.style.display = 'none';
        if(visualizerAnimationId) cancelAnimationFrame(visualizerAnimationId);

        recorder.stopRecording(function() {
             recordedWavBlob = recorder.getBlob();
             if (audioContext) audioContext.close();
             if (mediaStream) mediaStream.getTracks().forEach(t => t.stop());
             
             const audioUrl = URL.createObjectURL(recordedWavBlob);
             wsRecord.load(audioUrl);
             analyzeRecording();
        });
    }
}

function showLoadingPage() {
    const micBtn = document.getElementById('newMicBtn');
    const statusText = document.getElementById('newMicStatusText');
    if(micBtn) micBtn.classList.add('processing');
    if(statusText) statusText.innerText = 'Memproses...';

    document.getElementById('instructionsCard').style.display = 'none';
    document.getElementById('resultDetailsWrapper').style.display = 'none';
    document.getElementById('unifiedProcessingLoader').style.display = 'flex';
    
    document.getElementById('domEmotionEmpty').style.display = 'block';
    document.getElementById('domEmotionResult').style.display = 'none';
    document.getElementById('domSukuEmpty').style.display = 'block';
    document.getElementById('domSukuResult').style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showResultPage(result, source = 'record') {
    currentResult = result;
    currentSource = source;

    const micBtn = document.getElementById('newMicBtn');
    const statusText = document.getElementById('newMicStatusText');
    if(micBtn) {
        micBtn.classList.remove('processing');
        micBtn.classList.remove('recording');
    }
    if(statusText) statusText.innerText = 'Klik untuk mulai rekam';

    document.getElementById('unifiedProcessingLoader').style.display = 'none';
    document.getElementById('instructionsCard').style.display = 'none';
    document.getElementById('resultDetailsWrapper').style.display = 'block';
    
    document.getElementById('saveLoading').style.display = 'none';
    document.getElementById('saveSuccess').style.display = 'none';
    document.getElementById('saveError').style.display = 'none'; 

    document.getElementById('domEmotionEmpty').style.display = 'none';
    document.getElementById('domEmotionResult').style.display = 'flex';
    document.getElementById('domSukuEmpty').style.display = 'none';
    document.getElementById('domSukuResult').style.display = 'flex';

    fillResult('res_', result);
    window.scrollTo({ top: 150, behavior: 'smooth' });
}

function resetToInput() {
    const micBtn = document.getElementById('newMicBtn');
    const statusText = document.getElementById('newMicStatusText');
    if(micBtn) {
        micBtn.classList.remove('processing');
        micBtn.classList.remove('recording');
    }
    if(statusText) statusText.innerText = 'Klik untuk mulai rekam';

    document.getElementById('unifiedProcessingLoader').style.display = 'none';
    document.getElementById('resultDetailsWrapper').style.display = 'none';
    document.getElementById('instructionsCard').style.display = 'flex';
    
    document.getElementById('domEmotionEmpty').style.display = 'block';
    document.getElementById('domEmotionResult').style.display = 'none';
    document.getElementById('domSukuEmpty').style.display = 'block';
    document.getElementById('domSukuResult').style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function startRealtimeVisualizer() {
    const canvas = document.getElementById('recordingVisualizer');
    if(!canvas) return;
    const ctx = canvas.getContext('2d');
    const width = canvas.width = canvas.clientWidth;
    const height = canvas.height = canvas.clientHeight;
    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);
    const barWidth = 4;
    const gap = 4;
    const totalBarWidth = barWidth + gap;
    const maxBars = Math.ceil(width / totalBarWidth);
    const volumeHistory = new Array(maxBars).fill(2);
    
    function draw() {
        visualizerAnimationId = requestAnimationFrame(draw);
        if (isPaused) return;
        analyser.getByteTimeDomainData(dataArray);
        let maxVal = 0;
        for(let i = 0; i < bufferLength; i++) {
             const val = Math.abs(dataArray[i] - 128);
             if(val > maxVal) maxVal = val;
        }
        const barHeight = Math.max(2, (maxVal / 128) * height * 1.5);
        volumeHistory.push(barHeight);
        if (volumeHistory.length > maxBars) volumeHistory.shift();
        ctx.clearRect(0, 0, width, height);
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#94a3b8';
        ctx.lineWidth = barWidth;
        ctx.beginPath();
        let x = barWidth / 2;
        for (let i = 0; i < volumeHistory.length; i++) {
            const h = volumeHistory[i];
            const yCenter = height / 2;
            ctx.moveTo(x, yCenter - (h / 2));
            ctx.lineTo(x, yCenter + (h / 2));
            x += totalBarWidth;
        }
        ctx.stroke();
    }
    draw();
}

async function simpanKeLaravel(source, result, fileName = null, durationStr = null) {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch("{{ route('identifikasi.simpan') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": token, "Accept": "application/json", "Content-Type": "application/json" },
            body: JSON.stringify({
                sumber: source, file_suara: fileName, durasi: durationStr,
                hasil: result.dominant_emotion || result.mainEmotion || "-",
                akurasi: result.accuracy ?? null,
                distribution_by_emotion: result.distribution_by_emotion || null,
                distribution_by_suku: result.distribution_by_suku || null,
                nama: "{{ session('username') }}", email: "{{ session('email') }}",
                gender: "{{ session('gender') }}", usia: "{{ session('usia') }}"
            }),
        });
        const json = await response.json();
        if (!response.ok) throw new Error(json.message || 'Error');
        return json;
    } catch (e) { throw e; }
}

async function analyzeRecording() {
    if (!recordedWavBlob) return;
    const formData = new FormData();
    formData.append('file', recordedWavBlob, 'rekaman.wav');
    showLoadingPage();
    try {
        const response = await fetch(`${API_BASE}/analyze-audio`, { method: 'POST', body: formData });
        const result = await response.json();
        if (!response.ok) { resetToInput(); throw new Error(); }
        lastRecordResult = result;
        showResultPage(result, 'record');
        document.getElementById('saveLoading').style.display = 'flex';
        try {
            await simpanKeLaravel('record', result, "rekaman.wav", formatTime(wsRecord.getDuration()));
            document.getElementById('saveLoading').style.display = 'none';
            document.getElementById('saveSuccess').style.display = 'flex';
        } catch (e) {
            document.getElementById('saveLoading').style.display = 'none';
            document.getElementById('saveError').style.display = 'flex';
        }
    } catch (err) {
        resetToInput();
        Swal.fire({ icon: 'error', title: 'Koneksi Gagal', confirmButtonColor: '#0c56d0' });
    }
}

function fillResult(prefix, result) {
  const dist = result.distribution_by_emotion || {};
  const sukuDist = result.distribution_by_suku || {};
  const emotions = ["Happy", "Sad", "Angry", "Surprised", "Neutral"];
  let dominantEmotion = "Neutral";
  let maxEmotionVal = 0;
  emotions.forEach(em => {
    const val = parseFloat(dist[em]?.percent ?? 0);
    const barVal = document.getElementById(prefix + em.toLowerCase() + "Val");
    const barDiv = document.getElementById(prefix + em.toLowerCase() + "Bar");
    if (barVal) barVal.innerText = val.toFixed(1) + '%';
    if (barDiv) barDiv.style.width = val + '%';
    if (val > maxEmotionVal) { maxEmotionVal = val; dominantEmotion = em; }
  });
  document.getElementById(prefix + 'emojiIcon').src = {"Happy": "./img/happy.png", "Sad": "./img/sad.png", "Angry": "./img/angry.png", "Surprised": "./img/surprised.png", "Neutral": "./img/neutral.png"}[dominantEmotion];
  document.getElementById(prefix + 'mainEmotion').innerText = {"Happy": "Bahagia", "Sad": "Sedih", "Angry": "Marah", "Surprised": "Terkejut", "Neutral": "Netral"}[dominantEmotion] || dominantEmotion;
  document.getElementById(prefix + 'mainPercent').innerText = maxEmotionVal.toFixed(1) + '%';
  let dominantSuku = "-", maxSukuVal = 0;
  const barChart = document.getElementById(prefix + 'sukuBars');
  if(barChart) {
      barChart.innerHTML = '';
      Object.keys(sukuDist).forEach(suku => {
        const val = parseFloat(sukuDist[suku]?.percent ?? 0);
        if (val > maxSukuVal) { maxSukuVal = val; dominantSuku = suku; }
        const row = document.createElement('div'); row.className = 'progress-row';
        row.innerHTML = `<div class="progress-label">${suku}</div><div class="progress-track-wrapper"><div class="progress-track-bg"><div class="progress-track-fill ${suku.toLowerCase()}-fill" style="width: ${val.toFixed(1)}%;"></div></div></div><div class="progress-value">${val.toFixed(1)}%</div>`;
        barChart.appendChild(row);
      });
  }
  document.getElementById(prefix + 'sukuEmojiIcon').src = {"Batak": "./img/batak.png", "Jawa": "./img/jawa.png", "Sunda": "./img/sunda.png", "Betawi": "./img/betawi.png", "Minang": "./img/minang.png"}[dominantSuku] || "./img/neutral.png";
  document.getElementById(prefix + 'sukuDominant').innerText = dominantSuku;
  document.getElementById(prefix + 'sukuDominantPercent').innerText = maxSukuVal.toFixed(1) + '%';
}

function previewResult() {
    if(!currentResult) return;
    document.getElementById('prev_sumber').value = currentSource;
    document.getElementById('prev_hasil').value = currentResult.dominant_emotion || currentResult.mainEmotion || "-";
    document.getElementById('prev_akurasi').value = currentResult.accuracy ?? 0;
    document.getElementById('prev_dist_emotion').value = JSON.stringify(currentResult.distribution_by_emotion || {});
    document.getElementById('prev_dist_suku').value = JSON.stringify(currentResult.distribution_by_suku || {});
    document.getElementById('prev_file_suara').value = "rekaman.wav";
    if(wsRecord) document.getElementById('prev_durasi').value = formatTime(wsRecord.getDuration());
    document.getElementById('previewForm').submit();
}
</script>
</body>
</html>