<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUARA KU - Identifikasi Emosi Suara</title>
    <link rel="icon" href="./img/favicon.png" type="image/png">
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
        <a href="{{ route('home.dashboard') }}">
            <img src="./img/logo-suarakuu.png" alt="Logo Suara Ku" width="130" class="logo-img">
        </a>
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
  {{-- ================== 1. INPUT SECTION ================== --}}
  <div id="inputSection">
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
          <div id="fileNameDisplay" class="file-name" style="margin-top:5px; font-weight:600; display:none; text-align:right;"></div>

          <!-- WaveSurfer Container for Upload -->
          <div id="uploadWaveform" style="width:100%; margin-top:10px;"></div>
           <!-- Controls for Upload Player -->
          <div class="audio-controls" style="margin-top:5px; justify-content:center;">
             <button id="btnPlayUpload" class="play-control" onclick="togglePlayUpload()">
                <i class="fa-solid fa-play" id="iconPlayUpload"></i>
             </button>
          </div>
          <div id="uploadTimer" style="text-align:center; margin-top:2px; font-weight:500; font-family:monospace; color:#333;">00:00</div>

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


  </div>

  {{-- ================== RECORD CONTAINER ================== --}}
  <div id="recordContainerWrapper" class="hidden-tab">
      <div class="recording-container">
        <h3 class="recording-title">Mengidentifikasi suara melalui rekaman langsung</h3>
        <p class="recording-description">
          Kenali emosi dalam rekaman suara Anda secara langsung menggunakan teknologi Speech Emotion Recognition (SER).
          Cukup rekam atau ucapkan suara Anda untuk mendeteksi emosi dan suku secara real-time.
        </p>
        <!-- Initial Prompt State -->
        <div id="recordingStartPrompt">
            <p class="click-text">Klik tombol mikrofon untuk mulai merekam</p>
            <div class="mic-button" id="micButton" onclick="toggleRecording()">
                <div class="mic-icon">🎙</div>
            </div>
        </div>

        <!-- Visualisasi Real-time saat merekam -->
        <div id="recordingVisualizerContainer" class="hidden" style="width:100%; height:100px; margin-bottom:20px; display:none;">
            <canvas id="recordingVisualizer" style="width:100%; height:100%;"></canvas>
        </div>

        <!-- Active Recording State (Hidden by default) -->
        <div id="recordingActiveControls" class="hidden" style="display: none; align-items: center; justify-content: space-between; width: 100%; max-width: 600px; margin: 20px auto; padding: 10px;">
            <!-- Timer Section -->
            <div style="display: flex; align-items: center; gap: 10px;">
                 <div class="red-dot-active" style="width: 20px; height: 20px; border-radius: 50%; background-color: #ef4444; border: 4px solid #fecaca;"></div>
                 <div class="timer" id="recordTimer" style="font-size: 1rem; font-weight: 500; font-family: monospace; color: #1e293b; margin: 0;">00:00 / 05:00</div>
            </div>

            <!-- Controls Section -->
            <div style="display: flex; align-items: center; gap: 15px;">
                <button id="btnPauseResume" onclick="pauseResumeRecording()" style="display: flex; align-items: center; gap: 8px; padding: 8px 20px; border: 2px solid #3b82f6; background: transparent; color: #3b82f6; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    <i class="fa-solid fa-pause"></i> <span id="pauseText">Pause</span>
                </button>
                <button onclick="stopRecording()" style="display: flex; align-items: center; gap: 8px; padding: 10px 24px; background-color: #3b82f6; border: none; color: white; border-radius: 8px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5); transition: all 0.2s;">
                    <div style="width: 12px; height: 12px; background-color: white; border-radius: 2px;"></div> Stop
                </button>
            </div>
        </div>

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


  </div>
  </div> {{-- inputSection --}}

  {{-- ================== 2. LOADING SECTION ================== --}}
  <div id="loadingSection">
      <div class="modern-loader">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
      </div>
      <p class="loading-text">Sedang Menganalisis Suara...</p>
      <p style="color: #64748b; font-size: 0.95rem;">Mohon tunggu, AI kami sedang mengenali emosi Anda</p>
  </div>


  {{-- ================== 3. RESULT SECTION (Unified) ================== --}}
  <div id="resultSection">
    <div class="unified-result-container">
        <!-- Tombol "Kembali / Analisis Lagi" di atas -->
        <button class="btn-back-home" onclick="resetToInput()">
            <i class="fa-solid fa-arrow-left"></i> Analisis Lagi
        </button>
        
        <!-- Reuse existing result structure but accessible via IDs -->
        <div id="unifiedResultBody" style="display: flex; flex-direction: column; gap: 0;">
            
            <div class="result-box-container" style="justify-content: space-between; align-items: stretch; gap: 20px; width: 100%; max-width: 1050px; margin: 0 auto;">
          
              <!-- 1. Emotion -->
              <div class="result-box" style="flex: 1; width: auto; max-width: none; background: #ffffff; border-radius: 15px; padding: 15px;">
                <h4 class="chart-title" style="text-align: center;">Hasil Identifikasi Emosi</h4>
                <div class="bar-chart">
                  <div class="bar-label">Happy &nbsp;<span id="res_happyVal">0%</span></div>
                  <div class="bar happy" id="res_happyBar" style="width:0%"></div>

                  <div class="bar-label">Sad &nbsp;<span id="res_sadVal">0%</span></div>
                  <div class="bar sad" id="res_sadBar" style="width:0%"></div>

                  <div class="bar-label">Angry &nbsp;<span id="res_angryVal">0%</span></div>
                  <div class="bar angry" id="res_angryBar" style="width:0%"></div>

                  <div class="bar-label">Surprised &nbsp;<span id="res_surprisedVal">0%</span></div>
                  <div class="bar surprised" id="res_surprisedBar" style="width:0%"></div>

                  <div class="bar-label">Neutral &nbsp;<span id="res_neutralVal">0%</span></div>
                  <div class="bar neutral" id="res_neutralBar" style="width:0%"></div>
                </div>
              </div>

              <!-- 2. Suku -->
               <div class="result-box" style="flex: 1; width: auto; max-width: none; background: #ffffff; border-radius: 15px; padding: 15px;">
                <h4 class="chart-title" style="text-align: center;">Hasil Identifikasi Suku</h4>
                <div class="bar-chart" id="res_sukuBars"></div>
              </div>
              
              <!-- 3. Dominant Result -->
              <div class="merged-result" id="res_mainMergedResult" style="flex: 0 0 200px; width: auto; max-width: none; margin: 0; padding: 15px 10px; display: flex; flex-direction: column; justify-content: center; gap: 20px; background: #ffffff; border-radius: 15px;">
                 <!-- Emotion Dom -->
                 <div class="merged-item" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                     <img src="./img/neutral.png" alt="emoji" id="res_emojiIcon" style="width: 60px; height: 60px; object-fit: contain; margin-bottom: 5px;">
                     <h3 id="res_mainEmotion" style="margin: 0; font-size: 1.1rem; color: #1e293b;">-</h3>
                     <p id="res_mainPercent" style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #3b82f6;">-</p>
                 </div>
                 
                 <!-- Separator -->
                 <div style="width: 80%; height: 1px; background: #cbd5e1; margin: 0 auto;"></div>

                 <!-- Suku Dom -->
                 <div class="merged-item" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                     <img src="./img/betawi.png" alt="emoji" id="res_sukuEmojiIcon" style="width: 60px; height: 60px; object-fit: contain; margin-bottom: 5px;">
                     <h3 id="res_sukuDominant" style="margin: 0; font-size: 1.1rem; color: #1e293b;">-</h3>
                     <p id="res_sukuDominantPercent" style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #3b82f6;">-</p>
                 </div>
              </div>

            </div>

            <!-- Email Notification (Replaces PDF Buttons) -->
            <!-- Email Notification & Save Status -->
            <!-- Email Notification & Save Status -->
            <div id="saveStatusContainer" style="margin-top:30px; width: 100%; max-width: 1050px; margin: 20px auto; display: flex; justify-content: space-between; align-items: center; gap: 20px;">
               
               <!-- Preview Button (Left Side) -->
               <div>
                   <button onclick="previewResult()" style="background-color: #0d9488; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; gap: 8px;">
                       <i class="fa-solid fa-eye"></i> Pratinjau Hasil
                   </button>
               </div>

               <!-- Status Messages (Right Side / Flex Grow) -->
               <div style="flex: 1; text-align: right;">
                   <!-- Loading State -->
                   <div id="saveLoading" style="display:none; color: #64748b; font-weight: 500;">
                      <i class="fa-solid fa-spinner fa-spin"></i> &nbsp; Menyimpan data & mengirim email...
                   </div>

                   <!-- Success State -->
                   <div id="saveSuccess" style="display:none; background: #dbeafe; color: #1e40af; padding: 15px; border-radius: 8px; border: 1px solid #bfdbfe; font-weight: 500; text-align: center;">
                      <i class="fa-solid fa-envelope-circle-check"></i> &nbsp;
                      Hasil analisis berhasil disimpan & dikirim ke email: <strong>{{ session('email') }}</strong>
                   </div>

                   <!-- Error State -->
                   <div id="saveError" style="display:none; background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; border: 1px solid #fecaca; font-weight: 500; text-align: center;">
                      <i class="fa-solid fa-circle-exclamation"></i> &nbsp;
                      <span id="saveErrorMessage">Gagal menyimpan data.</span>
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
        </div>
    </div>
  </div>
</section>

<script>
const API_BASE = "http://127.0.0.1:5000";
const MAX_RECORD_SECONDS = {{ $maxSeconds ?? 300 }}; // 5 minutes default
const MIN_RECORD_SECONDS = {{ $minSeconds ?? 180 }};
const MAX_RECORD_STR = formatTime(MAX_RECORD_SECONDS);

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
let isPaused = false; // New state
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
let currentResult = null; // Store active result for preview
let currentSource = null; // 'upload' or 'record'

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
        const duration = wsUpload.getDuration();
        document.getElementById('uploadTimer').innerText = formatTime(currentTime) + " / " + formatTime(duration);
    });
    wsUpload.on('ready', (duration) => {
        document.getElementById('uploadTimer').innerText = "00:00 / " + formatTime(duration);
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
/* ================= TIMER ================= */
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}
let recordingStartTime;
let timerInterval;
let pausedTime = 0; // Accumulate paused duration
let pauseStart = 0; // When pause started

function startTimer() {
    recordingStartTime = Date.now();
    pausedTime = 0;
    
    timerInterval = setInterval(() => {
        if(isPaused) return;

        const now = Date.now();
        const totalElapsed = Math.floor((now - recordingStartTime - pausedTime) / 1000);
        
        document.getElementById('recordTimer').textContent = `${formatTime(totalElapsed)} / ${MAX_RECORD_STR}`;

        if (totalElapsed >= MAX_RECORD_SECONDS && isRecording) {
            stopRecording(true); // true = force stop because limit reached
            alert('Waktu rekaman sudah mencapai batas maksimal yang diizinkan.');
        }
    }, 1000);
}
function stopTimer() { clearInterval(timerInterval); }

function pauseResumeRecording() {
    if(!isRecording || !recorder) return;
    
    const btn = document.getElementById('btnPauseResume');
    const icon = btn.querySelector('i');
    const text = document.getElementById('pauseText');

    if(isPaused) {
        // RESUME
        recorder.resumeRecording();
        isPaused = false;
        
        // Timer adjustment
        pausedTime += (Date.now() - pauseStart);
        
        icon.className = 'fa-solid fa-pause';
        text.innerText = 'Pause';
        
        // Resume Visualizer if needed (optional)
        if(audioContext && audioContext.state === 'suspended') audioContext.resume();
        
    } else {
        // PAUSE
        recorder.pauseRecording();
        isPaused = true;
        pauseStart = Date.now();
        
        icon.className = 'fa-solid fa-play';
        text.innerText = 'Resume';
        
         // Suspend Visualizer to save resources or just pause effect
        if(audioContext && audioContext.state === 'running') audioContext.suspend();
    }
}

function stopRecording(forceStop = false) {
    toggleRecording(forceStop); // Reuse existing logic but ensure it triggers stop
}

/* ============ HELPER WAVEFORM (DELETED) ============ */
// drawStaticWaveform & cursor functions deleted as replaced by WaveSurfer

/* ============ REKAM → WAV (RecordRTC) ============ */
async function toggleRecording(forceStop = false) {
    const promptDiv = document.getElementById('recordingStartPrompt');
    const controlsDiv = document.getElementById('recordingActiveControls');
    const vizContainer = document.getElementById('recordingVisualizerContainer'); // Ensure this defined in HTML

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
            isPaused = false;
            
            // UI Update
            promptDiv.style.display = 'none';
            controlsDiv.style.display = 'flex';
            controlsDiv.classList.remove('hidden');
            
            // Re-show visualizer if it was hidden
            if(vizContainer) {
                 vizContainer.classList.remove('hidden');
                 vizContainer.style.display = 'block'; // Ensure display
            }
            
            startRealtimeVisualizer(); 
            startTimer();
        } catch (err) {
            alert('Gagal mengakses mikrofon: ' + err.message);
            console.error(err);
        }
    } else {
        // STOP RECORDING
        const duration = Math.floor((Date.now() - recordingStartTime - pausedTime) / 1000);

        // Check Min Duration
        if (!forceStop && duration < MIN_RECORD_SECONDS) {
            const minMins = Math.floor(MIN_RECORD_SECONDS / 60);
             Swal.fire({
                icon: 'warning',
                title: 'Durasi Kurang',
                text: `Durasi minimal adalah ${minMins} menit.`,
            });
            return; // Don't stop
        }

        stopTimer();
        isRecording = false;
        
        // Reset UI
        controlsDiv.style.display = 'none';
        controlsDiv.classList.add('hidden');
        // promptDiv.style.display = 'block'; // Keep hidden until we decide what to show? Or show result?
        // Actually, existing flow shows "analyze/cancel" buttons in `recordPlayerSection`.
        // So promptDiv stays hidden until Cancel is clicked.
        
        if(vizContainer) {
            vizContainer.classList.add('hidden');
            vizContainer.style.display = 'none';
        }
        if(visualizerAnimationId) cancelAnimationFrame(visualizerAnimationId);

        // Stop RecordRTC
        recorder.stopRecording(function() {
             recordedWavBlob = recorder.getBlob();
             
             // Stop Streams
             if (audioContext) audioContext.close();
             if (mediaStream) mediaStream.getTracks().forEach(t => t.stop());
             
             // Load into WaveSurfer
             const audioUrl = URL.createObjectURL(recordedWavBlob);
             wsRecord.load(audioUrl);
             
             document.getElementById('recordPlayerSection').classList.remove('hidden');
        });
    }
}


/* ============ LOADING HELPERS ============ */
/* ============ PAGE NAVIGATION ============ */
function showLoadingPage() {
    document.getElementById('inputSection').style.display = 'none';
    document.getElementById('resultSection').style.display = 'none';
    document.getElementById('loadingSection').style.display = 'flex';
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showResultPage(result, source = 'unknown') {
    currentResult = result;
    currentSource = source;

    document.getElementById('loadingSection').style.display = 'none';
    document.getElementById('inputSection').style.display = 'none';
    const resSec = document.getElementById('resultSection');
    resSec.style.display = 'block'; 
    
    // Reset Save Status
    document.getElementById('saveLoading').style.display = 'none';
    document.getElementById('saveSuccess').style.display = 'none';
    document.getElementById('saveError').style.display = 'none'; 
    
    // Hide Logo / Header
    const header = document.querySelector('.section-header');
    if(header) header.style.display = 'none';

    // Fill data
    fillResult('res_', result);
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetToInput() {
    document.getElementById('loadingSection').style.display = 'none';
    document.getElementById('resultSection').style.display = 'none';
    document.getElementById('inputSection').style.display = 'block';
    
    // Show Header again
    const header = document.querySelector('.section-header');
    if(header) header.style.display = 'block';

    window.scrollTo({ top: 0, behavior: 'smooth' });
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
        if (isPaused) return;
        
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
/* ============ SIMPAN KE LARAVEL ============ */
async function simpanKeLaravel(source, result, fileName = null, durationStr = null) {
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

        const response = await fetch("{{ route('identifikasi.simpan') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Accept": "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                sumber: source,
                file_suara: fileName,
                durasi: durationStr,

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

        const json = await response.json();

        if (!response.ok) {
            throw new Error(json.message || 'Server returned error ' + response.status);
        }
        
        return json;

    } catch (e) {
        console.error('Gagal menyimpan rekap ke Laravel:', e);
        throw e; // Rethrow to handle in caller
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

    showLoadingPage();

    try {
        const response = await fetch(`${API_BASE}/analyze-audio`, {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        if (!response.ok) {
            resetToInput();
            throw new Error(text);
        }

        const result = JSON.parse(text);
        lastRecordResult = result; 
        
        const fileName = "rekaman_" + Date.now() + ".wav";
        const durationStr = formatTime(wsRecord.getDuration());
        

        
        showResultPage(result, 'record');

        // Handle Saving & Email
        const saveLoading = document.getElementById('saveLoading');
        const saveSuccess = document.getElementById('saveSuccess');
        const saveError   = document.getElementById('saveError');
        
        saveLoading.style.display = 'block';

        try {
            await simpanKeLaravel('record', result, fileName, durationStr);
            saveLoading.style.display = 'none';
            saveSuccess.style.display = 'block';
        } catch (e) {
            saveLoading.style.display = 'none';
            saveError.style.display = 'block';
            document.getElementById('saveErrorMessage').innerText = "Gagal menyimpan: " + e.message;
        }

    } catch (err) {
        console.error(err);
        resetToInput();
        alert('Gagal menganalisis rekaman: ' + err.message);
    }
}

function cancelRecording() {
    recordedWavBlob = null;
    stopTimer();
    document.getElementById('recordTimer').textContent = `00:00 / ${MAX_RECORD_STR}`;

    const playerSection = document.getElementById('recordPlayerSection');
    playerSection.classList.add('hidden');
    
    // Reset WaveSurfer Record
    if(wsRecord) {
        wsRecord.empty();
        document.getElementById('iconPlayRecord').className = 'fa-solid fa-play';
    }



    // Show Prompt again
    document.getElementById('recordingStartPrompt').style.display = 'block';
    
    // Hide controls
    const controlsDiv = document.getElementById('recordingActiveControls');
    controlsDiv.style.display = 'none';
    controlsDiv.classList.add('hidden');

    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
    if (audioContext) audioContext.close();
    
    // Stop Realtime Visualizer
    const vizContainer = document.getElementById('recordingVisualizerContainer');
    if(vizContainer) vizContainer.classList.add('hidden');
    if(visualizerAnimationId) cancelAnimationFrame(visualizerAnimationId);

    isRecording = false;
    isPaused = false;
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
    
    // Hide Upload Area
    document.getElementById('uploadArea').style.display = 'none';
    
    // Auto-scroll to player section
    setTimeout(() => {
        document.getElementById('audioPlayerSection').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
}

function cancelUpload() {
  document.getElementById('fileInput').value = '';
  document.getElementById('audioPlayerSection').style.display = 'none';

  // Show Upload Area again
  document.getElementById('uploadArea').style.display = '';
  
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

  showLoadingPage();

  try {
    const response = await fetch(`${API_BASE}/analyze-audio`, {
      method: 'POST',
      body: formData
    });

    const text = await response.text();
    if (!response.ok) {
      resetToInput();
      throw new Error(text);
    }

    const result = JSON.parse(text);
    lastUploadResult = result; 
    
    const fileName = file ? file.name : null;
    const durationStr = formatTime(wsUpload.getDuration());

    showResultPage(result, 'upload');

    // Handle Saving & Email
    const saveLoading = document.getElementById('saveLoading');
    const saveSuccess = document.getElementById('saveSuccess');
    const saveError   = document.getElementById('saveError');
    
    saveLoading.style.display = 'block';

    try {
        await simpanKeLaravel('upload', result, fileName, durationStr);
        saveLoading.style.display = 'none';
        saveSuccess.style.display = 'block';
    } catch (e) {
        saveLoading.style.display = 'none';
        saveError.style.display = 'block';
        document.getElementById('saveErrorMessage').innerText = "Gagal menyimpan: " + e.message;
    }

  } catch (err) {
    console.error(err);
    resetToInput();
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
    if (barDiv) {
      barDiv.style.width = val + '%';
      if(val <= 0) {
          barDiv.style.backgroundColor = 'transparent';
          barDiv.style.boxShadow = 'none';
      } else {
          barDiv.style.backgroundColor = ''; // Reset
          barDiv.style.boxShadow = ''; 
      }
    }

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
  // Suku Color Map
  const sukuColorMap = {
    "Jawa": "#8B5A2B",
    "Sunda": "#2E8B57",
    "Batak": "#1C1C1C",
    "Minang": "#FFD700",
    "Betawi": "#c10303ff"
  };

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
        // Add spacing explicitly just in case
        label.innerHTML = `${suku} &nbsp; <span>${val.toFixed(2)}%</span>`;
        barChart.appendChild(label);

        // Create Single Bar (No Wrapper)
        const barDiv = document.createElement('div');
        barDiv.className = 'bar'; 
        // Apply class for CSS lookup if needed, though we use inline color
        barDiv.classList.add(suku.toLowerCase().replace(/\s+/g, '-'));

        barDiv.style.width = val + '%';
        
        // Determine Color
        const color = sukuColorMap[suku] || '#94a3b8';

        if (val <= 0) {
            barDiv.style.backgroundColor = 'transparent';
            barDiv.style.boxShadow = 'none';
        } else {
            barDiv.style.backgroundColor = color;
        }
        
        barChart.appendChild(barDiv);
      });
  }
  
  if(dominantSuku !== "-") {
      document.getElementById(prefix + 'sukuEmojiIcon').src = sukuEmojiIcon[dominantSuku] || "./img/neutral.png"; 
  }
  document.getElementById(prefix + 'sukuDominant').innerText = dominantSuku;
  document.getElementById(prefix + 'sukuDominantPercent').innerText = maxSukuVal.toFixed(2) + '%';
}

function previewResult() {
    if(!currentResult) {
        alert("Belum ada hasil analisis.");
        return;
    }

    // Populate Form
    document.getElementById('prev_sumber').value = currentSource;
    document.getElementById('prev_hasil').value = currentResult.dominant_emotion || currentResult.mainEmotion || "-";
    document.getElementById('prev_akurasi').value = currentResult.accuracy ?? 0;
    
    // JSON encode arrays
    document.getElementById('prev_dist_emotion').value = JSON.stringify(currentResult.distribution_by_emotion || {});
    document.getElementById('prev_dist_suku').value = JSON.stringify(currentResult.distribution_by_suku || {});

    // Duration & File Name
    if(currentSource === 'upload') {
        const fileInput = document.getElementById('fileInput');
        if(fileInput.files[0]) document.getElementById('prev_file_suara').value = fileInput.files[0].name;
        if(wsUpload) document.getElementById('prev_durasi').value = formatTime(wsUpload.getDuration());
    } else {
        document.getElementById('prev_file_suara').value = "rekaman.wav";
        if(wsRecord) document.getElementById('prev_durasi').value = formatTime(wsRecord.getDuration());
    }

    document.getElementById('previewForm').submit();
}


</script>
</body>
</html>