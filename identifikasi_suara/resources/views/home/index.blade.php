<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUARA KU - Identifikasi Emosi Suara</title>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <div class="logo">
        <img src="./img/logo-suarakuu.png" alt="Logo Suara Ku" width="130" class="logo-img">
    </div>
    <nav>
        <span class="username">
            Halo, <strong>{{ session('username') }}</strong>
        </span>
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="button" class="btn-logout" id="btnLogout" style="background:none; border:none; color:#f5f7fa;">
                <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
            </button>
        </form>
    </nav>
</header>

<section class="content-section">
  <div class="section-header">
    <img src="./img/emotions.png" alt="Logo Suara Ku" class="content-img">
  </div>

  {{-- TOGGLE BUTTONS --}}
  <div class="toggle-container">
      <button class="toggle-btn" id="btnRecord" onclick="switchTab('record')">Rekam Langsung</button>
      <button class="toggle-btn active" id="btnUpload" onclick="switchTab('upload')">Unggah File Audio</button>
  </div>

  {{-- ================== UPLOAD CONTAINER ================== --}}
  <div id="uploadContainerWrapper">
      <div class="upload-container">
        <h3 class="upload-title">Mengidentifikasi suara melalui file audio (.wav)</h3>
        <p class="upload-description">
          Kami menggunakan layanan Speech Emotion Recognition (SER) untuk membantu Anda mengidentifikasi emosi dalam suara. Unggah file audio (.wav) untuk mengenali emosi dalam suaramu.
        </p>
        <input type="file" id="fileInput" accept=".wav" style="display: none;" onchange="handleFileSelect(event)">
        <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileInput').click()">
          <div class="upload-icon">☁️</div>
          <p>Klik untuk memilih file audio</p>
        </div>

        <div id="audioPlayerSection" class="hidden" style="display:none;">
          <div id="fileNameDisplay" class="file-name" style="margin-top:10px; font-weight:600; display:none; text-align:right;"></div>

          <!-- WaveSurfer Container for Upload -->
          <div id="uploadWaveform" style="width:100%; margin-top:20px;"></div>
           <!-- Controls for Upload Player -->
          <div class="audio-controls" style="margin-top:10px; justify-content:center;">
             <button id="btnPlayUpload" class="play-control" onclick="togglePlayUpload()">
                <i class="fa-solid fa-play" id="iconPlayUpload"></i>
             </button>
          </div>
          <div id="uploadTimer" style="text-align:center; margin-top:5px; font-weight:500; font-family:monospace; color:#333;">00:00</div>

          <div class="action-buttons">
            <button class="btn-mulai" onclick="analyzeAudio()">Mulai Analisis</button>
            <button class="btn-batal" onclick="cancelUpload()">Batal</button>
          </div>
        </div>

        <div id="uploadLoading" class="loading-box" style="display:none;">
          <div class="spinner"></div>
          <p>menganalisis audio...</p>
          <div class="fake-progress">
            <div class="fake-progress-bar" id="uploadProgressBar"></div>
          </div>
        </div>
      </div>

      <div id="uploadResultSection" style="display:none;">
        <div class="result-box-container">
          <div class="result-box">
            <h4 class="chart-title">Hasil Identifikasi Emosi</h4>
            <div class="bar-chart">
              <div class="bar-label">Happy <span id="u_happyVal">0%</span></div>
              <div class="bar happy" id="u_happyBar" style="width:0%"></div>

              <div class="bar-label">Sad <span id="u_sadVal">0%</span></div>
              <div class="bar sad" id="u_sadBar" style="width:0%"></div>

              <div class="bar-label">Angry <span id="u_angryVal">0%</span></div>
              <div class="bar angry" id="u_angryBar" style="width:0%"></div>

              <div class="bar-label">Surprised <span id="u_surprisedVal">0%</span></div>
              <div class="bar surprised" id="u_surprisedBar" style="width:0%"></div>

              <div class="bar-label">Neutral <span id="u_neutralVal">0%</span></div>
              <div class="bar neutral" id="u_neutralBar" style="width:0%"></div>
            </div>
          </div>

          <div class="result-box">
            <h4 class="chart-title">Hasil Identifikasi Suku</h4>
            <div class="bar-chart" id="u_sukuBars"></div>
          </div>
        </div>

        <div class="main-result merged-result" id="u_mainMergedResult">
          <div class="merged-item" style="display: flex; align-items: center; gap: 15px; justify-content: center;">
              <img src="./img/neutral.png" id="u_emojiIcon" style="width: 80px; height: 80px; object-fit: contain;">
              <div style="text-align: left;">
                  <h3 id="u_mainEmotion" style="margin: 0; font-size: 1.5rem; color: #1e293b;">-</h3>
                  <p id="u_mainPercent" style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #3b82f6;">-</p>
              </div>
          </div>
          <div class="separator"></div>
          <div class="merged-item" style="display: flex; align-items: center; gap: 15px; justify-content: center;">
              <img src="./img/betawi.png" id="u_sukuEmojiIcon" style="width: 100px; height: 100px; object-fit: contain;">
              <div style="text-align: left;">
                  <h3 id="u_sukuDominant" style="margin: 0; font-size: 1.5rem; color: #1e293b;">-</h3>
                  <p id="u_sukuDominantPercent" style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #3b82f6;">-</p>
              </div>
          </div>
        </div>

        <div style="text-align:right; margin-top:10px;">
          <button class="btn-save" onclick="generatePDF()" style="margin-top:0;">
              Simpan Hasil ke PDF
          </button>
        </div>
      </div>
  </div>

  {{-- ================== RECORD CONTAINER ================== --}}
  <div id="recordContainerWrapper" class="hidden-tab">
      <div class="recording-container">
        <h3 class="recording-title">Mengidentifikasi suara melalui rekaman langsung</h3>
        <p class="recording-description">
          Kenali emosi dalam rekaman suara Anda secara langsung menggunakan teknologi Speech Emotion Recognition (SER).
          Cukup rekam atau ucapkan suara Anda untuk mendeteksi emosi dan suku secara real-time.
        </p>
        <p class="click-text">Klik tombol mikrofon untuk mulai merekam</p>

        <div class="mic-button" id="micButton" onclick="toggleRecording()">
            <div class="mic-icon">🎙</div>
        </div>

        <!-- Visualisasi Real-time saat merekam -->
        <div id="recordingVisualizerContainer" class="hidden" style="width:100%; height:100px; margin-bottom:20px;">
            <canvas id="recordingVisualizer" style="width:100%; height:100%;"></canvas>
        </div>

        <div id="recordingIndicator" class="recording-indicator hidden">
            <div class="red-dot"></div>
            <span>Merekam...</span>
        </div>

        <div class="timer" id="recordTimer">00:00</div>

        <div id="recordPlayerSection" class="hidden">
           <!-- WaveSurfer Container for Record -->
           <div id="recordWaveformPlayer" style="width:100%; margin-top:20px;"></div>
           
           <!-- Controls for Record Player -->
           <div class="audio-controls" style="margin-top:10px; justify-content:center;">
              <button id="btnPlayRecord" class="play-control" onclick="togglePlayRecord()">
                 <i class="fa-solid fa-play" id="iconPlayRecord"></i>
              </button>
           </div>
           <div id="recordTimerPlayback" style="text-align:center; margin-top:5px; font-weight:500; font-family:monospace; color:#333;">00:00</div>

           <div class="action-buttons">
               <button class="btn-mulai" onclick="analyzeRecording()">Mulai Analisis</button>
               <button class="btn-batal" onclick="cancelRecording()">Batal</button>
           </div>
        </div>

        <div id="recordLoading" class="loading-box" style="display:none;">
          <div class="spinner"></div>
          <p>menganalisis rekaman...</p>
          <div class="fake-progress">
            <div class="fake-progress-bar" id="recordProgressBar"></div>
          </div>
        </div>
      </div>

      <div id="recordResultSection" class="result-container" style="display:none; margin-top:20px;">
        <div class="result-box-container">
          <div class="result-box">
            <h4 class="chart-title">Hasil Identifikasi Emosi</h4>
            <div class="bar-chart">
              <div class="bar-label">Happy <span id="r_happyVal">0%</span></div>
              <div class="bar happy" id="r_happyBar" style="width:0%"></div>

              <div class="bar-label">Sad <span id="r_sadVal">0%</span></div>
              <div class="bar sad" id="r_sadBar" style="width:0%"></div>

              <div class="bar-label">Angry <span id="r_angryVal">0%</span></div>
              <div class="bar angry" id="r_angryBar" style="width:0%"></div>

              <div class="bar-label">Surprised <span id="r_surprisedVal">0%</span></div>
              <div class="bar surprised" id="r_surprisedBar" style="width:0%"></div>

              <div class="bar-label">Neutral <span id="r_neutralVal">0%</span></div>
              <div class="bar neutral" id="r_neutralBar" style="width:0%"></div>
            </div>
          </div>

          <div class="result-box">
            <h4 class="chart-title">Hasil Identifikasi Suku</h4>
            <div class="bar-chart" id="r_sukuBars"></div>
          </div>
        </div>

        <div class="main-result merged-result" id="r_mainMergedResult">
            <div class="merged-item" style="display: flex; align-items: center; gap: 15px; justify-content: center;">
                <img src="./img/neutral.png" alt="emoji" id="r_emojiIcon" style="width: 80px; height: 80px; object-fit: contain;">
                <div style="text-align: left;">
                    <h3 id="r_mainEmotion" style="margin: 0; font-size: 1.5rem; color: #1e293b;">-</h3>
                    <p id="r_mainPercent" style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #3b82f6;">-</p>
                </div>
            </div>
            <div class="separator"></div>
            <div class="merged-item" style="display: flex; align-items: center; gap: 15px; justify-content: center;">
                <img src="./img/betawi.png" alt="emoji" id="r_sukuEmojiIcon" style="width: 80px; height: 80px; object-fit: contain;">
                <div style="text-align: left;">
                    <h3 id="r_sukuDominant" style="margin: 0; font-size: 1.5rem; color: #1e293b;">-</h3>
                    <p id="r_sukuDominantPercent" style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #3b82f6;">-</p>
                </div>
            </div>
        </div>

        <div style="text-align:right; margin-top:10px;">
          <button class="btn-save" onclick="generatePDFRecord()" style="margin-top:0;">
              Simpan Hasil ke PDF
          </button>
        </div>
      </div>
  </div>
</section>

<script>
const API_BASE = "http://127.0.0.1:5000";
const MAX_RECORD_SECONDS = {{ $maxSeconds ?? 300 }};
const MIN_RECORD_SECONDS = {{ $minSeconds ?? 180 }};

/* ===== MENU TOGGLE ===== */
function switchTab(tab) {
    const btnUpload = document.getElementById('btnUpload');
    const btnRecord = document.getElementById('btnRecord');
    const uploadDiv = document.getElementById('uploadContainerWrapper');
    const recordDiv = document.getElementById('recordContainerWrapper');

    if (tab === 'upload') {
        btnUpload.classList.add('active');
        btnRecord.classList.remove('active');
        uploadDiv.classList.remove('hidden-tab');
        recordDiv.classList.add('hidden-tab');
    } else {
        btnRecord.classList.add('active');
        btnUpload.classList.remove('active');
        recordDiv.classList.remove('hidden-tab');
        uploadDiv.classList.add('hidden-tab');
    }
}

/* ===== GLOBAL STATE ===== */
let isRecording = false;
let audioContext;
let mediaStream;
// let mediaSource; // Tidak dipakai lagi untuk record, hanya visualizer
let analyser;
let visualizerAnimationId;

// RecordRTC state
let recorder; // RecordRTC instance
let recordedWavBlob = null;

// WaveSurfer instances
let wsUpload;
let wsRecord;

// Last Results for PDF
let lastUploadResult = null;
let lastRecordResult = null;

/* ===== INIT WAVESURFER ===== */
document.addEventListener('DOMContentLoaded', () => {
    // Init Visualizer Upload
    wsUpload = WaveSurfer.create({
        container: '#uploadWaveform',
        waveColor: '#cbd5e1',
        progressColor: '#0053d6',
        cursorColor: '#0053d6',
        barWidth: 2,
        barGap: 3,
        barRadius: 3,
        height: 100,
        responsive: true,
    });
    // Event Listeners for Upload Player
    wsUpload.on('play', () => {
        document.getElementById('iconPlayUpload').className = 'fa-solid fa-pause';
    });
    wsUpload.on('pause', () => {
        document.getElementById('iconPlayUpload').className = 'fa-solid fa-play';
    });
    wsUpload.on('finish', () => {
        document.getElementById('iconPlayUpload').className = 'fa-solid fa-play';
    });
    // Timer events for Upload
    wsUpload.on('timeupdate', (currentTime) => {
        document.getElementById('uploadTimer').innerText = formatTime(currentTime);
    });
    wsUpload.on('ready', (duration) => {
        document.getElementById('uploadTimer').innerText = "00:00";
    });

    // Init Visualizer Record (Playback)
    wsRecord = WaveSurfer.create({
        container: '#recordWaveformPlayer',
        waveColor: '#cbd5e1',
        progressColor: '#22c55e',
        cursorColor: '#22c55e',
        barWidth: 2,
        barGap: 3,
        barRadius: 3,
        height: 100,
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
        document.getElementById('recordTimerPlayback').innerText = formatTime(currentTime);
    });
    wsRecord.on('ready', (duration) => {
        document.getElementById('recordTimerPlayback').innerText = "00:00";
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
                confirmButtonColor: '#0053d6',
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

function togglePlayUpload() {
    if(wsUpload) wsUpload.playPause();
}
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
function startTimer() {
    recordingStartTime = Date.now();
    timerInterval = setInterval(() => {
        // ... (existing timer logic)
        const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
        document.getElementById('recordTimer').textContent = formatTime(elapsed);

        if (elapsed >= MAX_RECORD_SECONDS && isRecording) {
            toggleRecording(true); // true = force stop because limit reached
            alert('Waktu rekaman sudah mencapai batas maksimal yang diizinkan.');
        }
    }, 1000);
}
function stopTimer() { clearInterval(timerInterval); }

/* ============ HELPER WAVEFORM (DELETED) ============ */
// drawStaticWaveform & cursor functions deleted as replaced by WaveSurfer

/* ============ REKAM → WAV (RecordRTC) ============ */
async function toggleRecording(forceStop = false) {
    const micButton = document.getElementById('micButton');
    const indicator = document.getElementById('recordingIndicator');

    if (!isRecording) {
        // START RECORDING
        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            
            // 1. Setup Visualizer Real-time
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 2048;
            analyser.smoothingTimeConstant = 0.8;
            
            const source = audioContext.createMediaStreamSource(mediaStream);
            source.connect(analyser); 
            
            // 2. Setup RecordRTC
            recorder = new RecordRTC(mediaStream, {
                type: 'audio',
                mimeType: 'audio/wav',
                recorderType: RecordRTC.StereoAudioRecorder, // Force WAV
                numberOfAudioChannels: 1, // Mono
                desiredSampRate: 16000 
            });

            recorder.startRecording();
            
            isRecording = true;
            micButton.classList.add('recording');
            
            document.getElementById('recordingIndicator').classList.add('hidden'); 
            const vizContainer = document.getElementById('recordingVisualizerContainer');
            vizContainer.classList.remove('hidden');
            
            startRealtimeVisualizer(); 
            startTimer();
        } catch (err) {
            alert('Gagal mengakses mikrofon: ' + err.message);
            console.error(err);
        }
    } else {
        // STOP RECORDING
        const duration = Math.floor((Date.now() - recordingStartTime) / 1000);

        // Check Min Duration (unless forceStop by Timer)
        if (!forceStop && duration < MIN_RECORD_SECONDS) {
            const minMins = Math.floor(MIN_RECORD_SECONDS / 60);
             Swal.fire({
                icon: 'warning',
                title: 'Durasi Kurang',
                text: `Durasi minimal adalah ${minMins} menit.`,
            });
            return; // Don't stop yet, let user continue
        }

        stopTimer();
        isRecording = false;
        micButton.classList.remove('recording');
        
        document.getElementById('recordingVisualizerContainer').classList.add('hidden');
        if(visualizerAnimationId) cancelAnimationFrame(visualizerAnimationId);

        // Stop RecordRTC
        recorder.stopRecording(function() {
             recordedWavBlob = recorder.getBlob();
             
             // Stop Streams
             if (audioContext) audioContext.close();
             if (mediaStream) mediaStream.getTracks().forEach(t => t.stop());
             
             // Load into WaveSurfer
             // wsRecord.loadBlob(recordedWavBlob); // WaveSurfer 7 supports loadBlob or url
             const audioUrl = URL.createObjectURL(recordedWavBlob);
             wsRecord.load(audioUrl);
             
             document.getElementById('recordPlayerSection').classList.remove('hidden');
        });
    }
}


/* ============ LOADING HELPERS ============ */
function startLoadingUpload() {
  const box = document.getElementById('uploadLoading');
  const btn = document.querySelector('.upload-container .btn-mulai');
  if (box) box.style.display = 'flex';
  if (btn) btn.disabled = true;
  let prog = 10;
  const bar = document.getElementById('uploadProgressBar');
  box._interval = setInterval(() => {
    prog += Math.floor(Math.random() * 8) + 3;
    if (prog > 90) prog = 90;
    if (bar) bar.style.width = prog + '%';
  }, 400);
}
function stopLoadingUpload(success = true) {
  const box = document.getElementById('uploadLoading');
  const btn = document.querySelector('.upload-container .btn-mulai');
  const bar = document.getElementById('uploadProgressBar');
  if (bar) bar.style.width = success ? '100%' : '0%';
  if (box && box._interval) clearInterval(box._interval);
  setTimeout(() => { if (box) box.style.display = 'none'; }, 450);
  if (btn) btn.disabled = false;
}

function startLoadingRecord() {
  const box = document.getElementById('recordLoading');
  const btn = document.querySelector('#recordPlayerSection .btn-mulai');
  if (box) box.style.display = 'flex';
  if (btn) btn.disabled = true;
  let prog = 10;
  const bar = document.getElementById('recordProgressBar');
  box._interval = setInterval(() => {
    prog += Math.floor(Math.random() * 8) + 3;
    if (prog > 90) prog = 90;
    if (bar) bar.style.width = prog + '%';
  }, 400);
}
function stopLoadingRecord(success = true) {
  const box = document.getElementById('recordLoading');
  const btn = document.querySelector('#recordPlayerSection .btn-mulai');
  const bar = document.getElementById('recordProgressBar');
  if (bar) bar.style.width = success ? '100%' : '0%';
  if (box && box._interval) clearInterval(box._interval);
  setTimeout(() => { if (box) box.style.display = 'none'; }, 450);
  if (btn) btn.disabled = false;
}

/* ============ REAL-TIME VISUALIZER (SCROLLING) ============ */
function startRealtimeVisualizer() {
    const canvas = document.getElementById('recordingVisualizer');
    const ctx = canvas.getContext('2d');
    const width = canvas.width = canvas.clientWidth;
    const height = canvas.height = canvas.clientHeight;
    
    // Config
    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);
    
    const barWidth = 4;
    const gap = 4;
    const totalBarWidth = barWidth + gap;
    const maxBars = Math.ceil(width / totalBarWidth);
    
    // Array untuk menyimpan history volume (scrolling visualizer)
    // Kita isi dengan nilai 0 (atau nilai minim) di awal
    const volumeHistory = new Array(maxBars).fill(2); // fill min height
    
    function draw() {
        visualizerAnimationId = requestAnimationFrame(draw);
        
        analyser.getByteTimeDomainData(dataArray);
        
        // Hitung volume rata-rata/maksimum frame ini
        let maxVal = 0;
        for(let i = 0; i < bufferLength; i++) {
             const val = Math.abs(dataArray[i] - 128);
             if(val > maxVal) maxVal = val;
        }
        
        // Normalize height
        const barHeight = Math.max(2, (maxVal / 128) * height * 1.5);
        
        // Push ke history, remove yang paling lama (efek jalan dari kanan ke kiri)
        volumeHistory.push(barHeight);
        if (volumeHistory.length > maxBars) {
            volumeHistory.shift();
        }
        
        ctx.clearRect(0, 0, width, height);
        
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = barWidth;
        
        ctx.beginPath();
        let x = barWidth / 2; // Start position
        
        for (let i = 0; i < volumeHistory.length; i++) {
            const h = volumeHistory[i];
            
            const yCenter = height / 2;
            const yTop = yCenter - (h / 2);
            const yBottom = yCenter + (h / 2);
            
            ctx.moveTo(x, yTop);
            ctx.lineTo(x, yBottom);
            
            x += totalBarWidth;
        }
        ctx.stroke();
    }
    
    draw();
}

/* ============ SIMPAN KE LARAVEL ============ */
async function simpanKeLaravel(source, result, fileName = null) {
    try {
        const token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        const nama   = "{{ session('username') }}";
        const email  = "{{ session('email') }}";
        const gender = "{{ session('gender') }}";
        const usia   = "{{ session('usia') }}";

        const dominantEmotion = result.dominant_emotion || result.mainEmotion || "-";
        const accuracy        = result.accuracy ?? null;

        await fetch("{{ route('identifikasi.simpan') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Accept": "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                sumber: source,
                file_suara: fileName,

                hasil:   dominantEmotion,
                akurasi: accuracy,

                distribution_by_emotion: result.distribution_by_emotion || null,
                distribution_by_suku:    result.distribution_by_suku || null,

                nama:   nama,
                email:  email,
                gender: gender,
                usia:   usia,
            }),
        });
    } catch (e) {
        console.error('Gagal menyimpan rekap ke Laravel:', e);
    }
}

/* ============ ANALISIS REKAMAN ============ */
async function analyzeRecording() {
    if (!recordedWavBlob) {
        alert("Tidak ada rekaman WAV untuk dianalisis!");
        return;
    }

    const formData = new FormData();
    formData.append('file', recordedWavBlob, 'rekaman.wav');

    startLoadingRecord();

    try {
        const response = await fetch(`${API_BASE}/analyze-audio`, {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        if (!response.ok) {
            stopLoadingRecord(false);
            throw new Error(text);
        }

        const result = JSON.parse(text);
        lastRecordResult = result; // Store for PDF
        document.getElementById('recordResultSection').style.display = 'flex';
        fillResult('r_', result);

        simpanKeLaravel('record', result, null);
        stopLoadingRecord(true);
    } catch (err) {
        console.error(err);
        alert('Gagal menganalisis rekaman: ' + err.message);
    }
}

function cancelRecording() {
    recordedWavBlob = null;
    stopTimer();
    document.getElementById('recordTimer').textContent = '00:00';

    const playerSection = document.getElementById('recordPlayerSection');
    playerSection.classList.add('hidden');
    
    // Reset WaveSurfer Record
    if(wsRecord) {
        wsRecord.empty();
        // Icon update handled by event listener, but force reset here just in case
        // document.getElementById('iconPlayRecord').className = 'fa-solid fa-play'; 
        // Actually event listener might not fire on empty(), so better reset manually if needed or leave it.
        // Let's reset it manually to be safe.
        document.getElementById('iconPlayRecord').className = 'fa-solid fa-play';
    }

    document.getElementById('recordResultSection').style.display = 'none';

    const micButton = document.getElementById('micButton');
    const indicator = document.getElementById('recordingIndicator');
    micButton.classList.remove('recording');
    indicator.classList.add('hidden');

    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
    if (audioContext) audioContext.close();
    
    // Stop Realtime Visualizer
    document.getElementById('recordingVisualizerContainer').classList.add('hidden');
    if(visualizerAnimationId) cancelAnimationFrame(visualizerAnimationId);

    isRecording = false;
}

/* ============ UPLOAD FILE ============ */
async function handleFileSelect(e) {
    const file = e.target.files[0];
    if (!file) return;

    const maxSize = 500 * 1024 * 1024;

    if (file.size > maxSize) {
        alert("Ukuran file terlalu besar! Maksimal 500MB.");
        e.target.value = "";
        return;
    }

    const fileNameDiv = document.getElementById("fileNameDisplay");
    fileNameDiv.textContent = file.name;
    fileNameDiv.style.display = "block";

    const audioUrl = URL.createObjectURL(file);
    
    document.getElementById('audioPlayerSection').style.display = 'block';
    
    // Load ke WaveSurfer Upload
    if(wsUpload) {
        wsUpload.load(audioUrl);
    }
}

function cancelUpload() {
  document.getElementById('fileInput').value = '';
  document.getElementById('audioPlayerSection').style.display = 'none';
  document.getElementById('uploadResultSection').style.display = 'none';
  
  if(wsUpload) {
      wsUpload.empty();
      document.getElementById('iconPlayUpload').className = 'fa-solid fa-play';
  }
}

async function analyzeAudio() {
  const fileInput = document.getElementById('fileInput');
  const file = fileInput.files[0];
  if (!file) {
    alert('Tidak ada file audio yang diunggah!');
    return;
  }

  const formData = new FormData();
  formData.append('file', file);

  startLoadingUpload();

  try {
    const response = await fetch(`${API_BASE}/analyze-audio`, {
      method: 'POST',
      body: formData
    });

    const text = await response.text();
    if (!response.ok) {
      stopLoadingUpload(false);
      throw new Error(text);
    }

    const result = JSON.parse(text);
    lastUploadResult = result; // Store for PDF
    document.getElementById('uploadResultSection').style.display = 'flex';
    fillResult('u_', result);

    const fileName = file ? file.name : null;
    simpanKeLaravel('upload', result, fileName);

    stopLoadingUpload(true);
  } catch (err) {
    console.error(err);
    alert('Gagal menganalisis audio: ' + err.message);
  }
}

/* ============ RENDER HASIL ============ */
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

    if (barVal) barVal.innerText = val.toFixed(2) + '%';
    if (barDiv) barDiv.style.width = val + '%';

    if (val > maxEmotionVal) {
      maxEmotionVal = val;
      dominantEmotion = em;
    }
  });

  const emojiMap = {
    "Happy": "./img/happy.png",
    "Sad": "./img/sad.png",
    "Angry": "./img/angry.png",
    "Surprised": "./img/surprised.png",
    "Neutral": "./img/neutral.png"
  };

  const sukuEmojiIcon = {
    "Batak": "./img/batak.png",
    "Jawa": "./img/jawa.png",
    "Sunda": "./img/sunda.png",
    "Betawi": "./img/betawi.png",
    "Minang": "./img/minang.png"
  };

  document.getElementById(prefix + 'emojiIcon').src = emojiMap[dominantEmotion];
  document.getElementById(prefix + 'mainEmotion').innerText = dominantEmotion;
  document.getElementById(prefix + 'mainPercent').innerText = maxEmotionVal.toFixed(2) + '%';

  let dominantSuku = "-";
  let maxSukuVal = 0;

  const sukuKeys = Object.keys(sukuDist);
  const barChart = document.getElementById(prefix + 'sukuBars');

  // Bersihkan chart lama jika perlu, atau pastikan ID unik
  // Di sini asumsi elemen bar-chart kosong atau bisa di-append
  // Tapi struktur HTML statik tidak punya child dinamis untuk suku.
  // Kita harus generate bar secara dinamis atau asumsikan struktur sudah ada?
  // Kode asli TIDAK punya loop generate HTML untuk suku di bagian HTML,
  // tapi di JS ada `sukuKeys.forEach`.
  // Namun, kode JS di Step 8 tidak menunjukkan innerHTML injection untuk Suku.
  // Wait, line 94 di Step 8: <div class="bar-chart" id="u_sukuBars"></div>
  // This implies JS should inject content.
  // Let me check the original JS for filling suku result.
  // Line 798 in Step 8: `let barEl = document.getElementById(prefix + 'sukuBar_' + suku);`
  // It tries to find existing element, if not creates it.
  // OK, I'll allow the original logic to run.

  // NOTE: I am copying the logic from Step 8 roughly but I must ensure the fillResult function is complete.
  // The snippet in Step 8 was cutoff at line 800.
  // I will reconstruct the loop based on standard chart logic or what I can infer.

  if(barChart) {
      barChart.innerHTML = ''; // reset
      sukuKeys.forEach(suku => {
        const val = parseFloat(sukuDist[suku]?.percent ?? 0);
        if (val > maxSukuVal) {
          maxSukuVal = val;
          dominantSuku = suku;
        }
        
        // Create label
        const label = document.createElement('div');
        label.className = 'bar-label';
        label.innerHTML = `${suku} <span>${val.toFixed(2)}%</span>`;
        barChart.appendChild(label);

        // Create bar
        const barOuter = document.createElement('div');
        barOuter.className = 'bar'; // default gray/base
        // Kita bisa kasih warna khusus jika mau, atau biarkan default CSS.
        // Asumsi ada CSS .bar
        const barInner = document.createElement('div');
        barInner.style.height = '100%'; 
        barInner.style.width = val + '%';
        barInner.style.backgroundColor = '#4ade80'; // Greenish
        barOuter.appendChild(barInner);
        
        // Namun struktur HTML asli untuk emotion adalah: <div class="bar [emotion]" style="width:.."></div>
        // Suku mungkin butuh struktur serupa.
        // Simple structure:
        const barDiv = document.createElement('div');
        barDiv.className = 'bar';
        barDiv.style.width = val + '%';
        barDiv.style.backgroundColor = '#60a5fa'; // Blueish
        barChart.appendChild(barDiv);
      });
  }
  
  document.getElementById(prefix + 'sukuEmojiIcon').src = sukuEmojiIcon[dominantSuku]; 
  document.getElementById(prefix + 'sukuDominant').innerText = dominantSuku;
  document.getElementById(prefix + 'sukuDominantPercent').innerText = maxSukuVal.toFixed(2) + '%';
}

function generatePDF() {
    if(!lastUploadResult) return alert("Belum ada hasil analisis untuk disimpan!");
    createPDF('Upload', lastUploadResult);
}
function generatePDFRecord() {
    if(!lastRecordResult) return alert("Belum ada hasil analisis untuk disimpan!");
    createPDF('Rekaman', lastRecordResult);
}

function createPDF(sourceLabel, resultData) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // -- HEADER / TITLE --
    doc.setFontSize(16);
    doc.setFont("helvetica", "bold");
    doc.text("Hasil Identifikasi Suara", 105, 15, { align: "center" });

    doc.setFontSize(10);
    doc.setFont("helvetica", "normal");
    doc.text(`Sumber: ${sourceLabel}`, 105, 22, { align: "center" });
    doc.text(`Tanggal: ${new Date().toLocaleString()}`, 105, 27, { align: "center" });

    // -- USER INFO --
    const startY = 35;
    doc.setFontSize(11);
    doc.text(`Username : {{ session('username') }}`, 14, startY);
    doc.text(`Email    : {{ session('email') }}`, 14, startY + 6);
    doc.text(`Gender   : {{ session('gender') }}`, 14, startY + 12);
    doc.text(`Usia     : {{ session('usia') }}`, 14, startY + 18);

    // -- PREPARE TABLE DATA --
    // 1. Emotion Data
    const emoDist = resultData.distribution_by_emotion || {};
    const emoRows = Object.keys(emoDist).map(key => {
        return [key, parseFloat(emoDist[key].percent).toFixed(2) + " %"];
    }).sort((a,b) => parseFloat(b[1]) - parseFloat(a[1])); // Sort Descending

    // 2. Suku Data
    const sukuDist = resultData.distribution_by_suku || {};
    const sukuRows = Object.keys(sukuDist).map(key => {
        return [key, parseFloat(sukuDist[key].percent).toFixed(2) + " %"];
    }).sort((a,b) => parseFloat(b[1]) - parseFloat(a[1]));

    // -- DRAW TABLES --
    
    // Table 1: Emosi
    doc.text("Tabel Hasil Identifikasi Emosi", 14, startY + 30);
    doc.autoTable({
        startY: startY + 35,
        head: [['Emosi', 'Persentase (%)']],
        body: emoRows,
        theme: 'grid',
        headStyles: { fillColor: [59, 130, 246] }, // Blueish
    });

    // Table 2: Suku (below previous table)
    let finalY = doc.lastAutoTable.finalY + 15;
    doc.text("Tabel Hasil Identifikasi Suku", 14, finalY);
    doc.autoTable({
        startY: finalY + 5,
        head: [['Suku', 'Persentase (%)']],
        body: sukuRows,
        theme: 'grid',
        headStyles: { fillColor: [59, 130, 246] },
    });

    // Save
    const timestamp = new Date().toISOString().replace(/[:.]/g, "-");
    doc.save(`hasil_identifikasi_${timestamp}.pdf`);
}
</script>
</body>
</html>
