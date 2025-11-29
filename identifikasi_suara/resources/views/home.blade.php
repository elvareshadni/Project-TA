<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUARA KU - Identifikasi Emosi Suara</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

</head>
<body>
<header>
    <div class="logo">  
        <img src="./img/logo-suaraku.png" alt="Logo Suara Ku" width="100" class="logo-img">
    </div>
    <nav>
        <span class="username">
            Halo, <strong>{{ session('username') }}</strong>
        </span>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout" style="background:none; border:none; color:#1e3a5f;">
                <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
            </button>
        </form>
    </nav>
</header>

<section class="hero">
    <div class="hero-content">
        <h1>Layanan Identifikasi Emosi berdasarkan Fitur Suara</h1>
        <p class="hero-description">
            <strong>SUARA KU</strong> adalah platform identifikasi emosi berdasarkan fitur suara dengan pendekatan Speech Emotion Recognition (SER)
        </p>
        <button class="btn-rekam" onclick="scrollToSection('record')">Rekam Sekarang</button>
    </div>
    <div class="hero-image">
        <div class="animasi">
            <img src="./img/animasi.png" alt="animasi" width="550" class="logo-img">
        </div>
    </div>
</section>

<!-- ========== UPLOAD FILE ========== -->
<section class="content-section">
  <div class="section-header">
    <img src="./img/emotions.png" alt="Logo Suara Ku" class="content-img">
    
  </div>

  <div class="upload-container">
    <h3 class="upload-title">Mengidentifikasi suara dari file audio (.wav)</h3>
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
      <audio id="uploadedAudio" controls style="width:100%; margin-top:10px;"></audio>
      <div class="action-buttons">
        <button class="btn-mulai" onclick="analyzeAudio()">Mulai Analisis</button>
        <button class="btn-batal" onclick="cancelUpload()">Batal</button>
      </div>
    </div>

    <!-- loading upload -->
    <div id="uploadLoading" class="loading-box" style="display:none;">
      <div class="spinner"></div>
      <p>menganalisis audio...</p>
      <div class="fake-progress">
        <div class="fake-progress-bar" id="uploadProgressBar"></div>
      </div>
    </div>
  </div>

  <!-- HASIL UPLOAD -->
<div id="uploadResultSection" style="display:none;">

  <!-- WRAPPER UNTUK DUA BOX -->
  <div class="result-box-container">

    <!-- KOTAK EMOSI -->
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

    <!-- KOTAK SUKU -->
    <div class="result-box">
      <h4 class="chart-title">Hasil Identifikasi Suku</h4>
      <div class="bar-chart" id="u_sukuBars"></div>
    </div>

  </div>

  <!-- MERGED RESULT DI TENGAH BAWAH -->
  <div class="main-result merged-result" id="u_mainMergedResult">
      <div class="merged-item">
          <img src="./img/neutral.png" id="u_emojiIcon">
          <h3 id="u_mainEmotion">-</h3>
          <p id="u_mainPercent">-</p>
      </div>

      <div class="separator"></div>

      <div class="merged-item">
          <img src="./img/neutral.png" id="u_sukuEmojiIcon">
          <h3 id="u_sukuDominant">-</h3>
          <p id="u_sukuDominantPercent">-</p>
      </div>
  </div>

  <!-- BUTTON SIMPAN HASIL ANALISIS -->
  <div style="text-align:center; margin-top:25px;">
      <button class="btn-save" onclick="generatePDF()" style="margin-top:20px;">
          Simpan Hasil Analisis (PDF)
      </button>
  </div>

</div>
</section>

<!-- ========== REKAM ========== -->
<section class="content-section" style="background: #f5f7fa;" id="record">
    <div class="section-header">
        <h2 class="section-title">Rekam Suaramu Langsung</h2>
        <p class="section-subtitle">Deteksi emosi secara real-time dari rekaman suara</p>
    </div>

    <div class="recording-container">
        <p class="click-text">Klik tombol mikrofon untuk mulai merekam</p>
        <div class="mic-button" id="micButton" onclick="toggleRecording()">
            <div class="mic-icon">🎙</div>
        </div>

        <div id="recordingIndicator" class="recording-indicator hidden">
            <div class="red-dot"></div>
            <span>Merekam...</span>
        </div>

        <div class="timer" id="recordTimer">00:00</div>

        <div id="recordPlayerSection" class="hidden">
            <audio id="recordedAudioPlayer" controls style="width:100%; margin-top:10px;"></audio>
            <div class="action-buttons">
                <button class="btn-mulai" onclick="analyzeRecording()">Mulai Analisis</button>
                <button class="btn-batal" onclick="cancelRecording()">Batal</button>
            </div>
        </div>

        <!-- loading rekam -->
        <div id="recordLoading" class="loading-box" style="display:none;">
          <div class="spinner"></div>
          <p>menganalisis rekaman...</p>
          <div class="fake-progress">
            <div class="fake-progress-bar" id="recordProgressBar"></div>
          </div>
        </div>
    </div>

    <!-- HASIL REKAM -->
<div id="recordResultSection" class="result-container" style="display:none; margin-top:20px;">

  <!-- WRAPPER BOX EMOSI + SUKU -->
  <div class="result-box-container">
    
    <!-- KOTAK EMOSI -->
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

    <!-- KOTAK SUKU -->
    <div class="result-box">
      <h4 class="chart-title">Hasil Identifikasi Suku</h4>

      <div class="bar-chart" id="r_sukuBars">
        <!-- bar suku dari JS -->
      </div>
    </div>

  </div> 

  <!-- MERGED RESULT -->
  <div class="main-result merged-result" id="r_mainMergedResult">
      <div class="merged-item">
          <img src="./img/neutral.png" alt="emoji" id="r_emojiIcon">
          <h3 id="r_mainEmotion">-</h3>
          <p id="r_mainPercent">-</p>
      </div>

      <div class="separator"></div>

      <div class="merged-item">
          <img src="./img/neutral.png" alt="emoji" id="r_sukuEmojiIcon">
          <h3 id="r_sukuDominant">-</h3>
          <p id="r_sukuDominantPercent">-</p>
      </div>
  </div>

</div>


</section>

<script>
/* ===================== CONFIG ===================== */
const API_BASE = "http://127.0.0.1:5000";

/* ===================== GLOBAL STATE ===================== */
let isRecording = false;
let audioContext;
let mediaStream;
let mediaSource;
let processor;
let recordedChunks = [];
let recordedSampleRate = 44100;
let recordedWavBlob = null;

/* ===================== TIMER ===================== */
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, '0')}`;
}
let recordingStartTime;
let timerInterval;
function startTimer() {
    recordingStartTime = Date.now();
    timerInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
        document.getElementById('recordTimer').textContent = formatTime(elapsed);
    }, 1000);
}
function stopTimer() { clearInterval(timerInterval); }

/* ===================== REKAM → WAV ===================== */
async function toggleRecording() {
    const micButton = document.getElementById('micButton');
    const indicator = document.getElementById('recordingIndicator');

    if (!isRecording) {
        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            recordedSampleRate = audioContext.sampleRate;

            mediaSource = audioContext.createMediaStreamSource(mediaStream);
            processor = audioContext.createScriptProcessor(4096, 1, 1);
            recordedChunks = [];

            processor.onaudioprocess = function(e) {
                const inputData = e.inputBuffer.getChannelData(0);
                recordedChunks.push(new Float32Array(inputData));
            };

            mediaSource.connect(processor);
            processor.connect(audioContext.destination);

            isRecording = true;
            micButton.classList.add('recording');
            indicator.classList.remove('hidden');
            startTimer();
        } catch (err) {
            alert('Gagal mengakses mikrofon: ' + err.message);
        }
    } else {
        // stop
        stopTimer();
        isRecording = false;
        micButton.classList.remove('recording');
        indicator.classList.add('hidden');

        if (processor) processor.disconnect();
        if (mediaSource) mediaSource.disconnect();
        if (mediaStream) mediaStream.getTracks().forEach(t => t.stop());
        if (audioContext) audioContext.close();

        const wavBlob = exportWAV(recordedChunks, recordedSampleRate);
        recordedWavBlob = wavBlob;

        const audioUrl = URL.createObjectURL(wavBlob);
        document.getElementById('recordedAudioPlayer').src = audioUrl;
        document.getElementById('recordPlayerSection').classList.remove('hidden');
    }
}

/* ===== util WAV encoding ===== */
function mergeBuffers(chunks) {
    let length = 0;
    chunks.forEach(c => length += c.length);
    const result = new Float32Array(length);
    let offset = 0;
    chunks.forEach(c => {
        result.set(c, offset);
        offset += c.length;
    });
    return result;
}
function exportWAV(chunks, sampleRate) {
    const samples = mergeBuffers(chunks);
    const buffer = encodeWAV(samples, sampleRate);
    return new Blob([buffer], { type: 'audio/wav' });
}
function encodeWAV(samples, sampleRate) {
    const bytesPerSample = 2;
    const numChannels = 1;
    const blockAlign = numChannels * bytesPerSample;
    const byteRate = sampleRate * blockAlign;
    const dataSize = samples.length * bytesPerSample;
    const buffer = new ArrayBuffer(44 + dataSize);
    const view = new DataView(buffer);

    writeString(view, 0, 'RIFF');
    view.setUint32(4, 36 + dataSize, true);
    writeString(view, 8, 'WAVE');
    writeString(view, 12, 'fmt ');
    view.setUint32(16, 16, true);
    view.setUint16(20, 1, true);
    view.setUint16(22, numChannels, true);
    view.setUint32(24, sampleRate, true);
    view.setUint32(28, byteRate, true);
    view.setUint16(32, blockAlign, true);
    view.setUint16(34, 16, true);
    writeString(view, 36, 'data');
    view.setUint32(40, dataSize, true);

    let offset = 44;
    for (let i = 0; i < samples.length; i++, offset += 2) {
        let s = Math.max(-1, Math.min(1, samples[i]));
        view.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
    }
    return buffer;
}
function writeString(view, offset, string) {
    for (let i = 0; i < string.length; i++) {
        view.setUint8(offset + i, string.charCodeAt(i));
    }
}

/* ===================== LOADING HELPERS ===================== */
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

/* ===================== ANALISIS REKAMAN ===================== */
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
        document.getElementById('recordResultSection').style.display = 'flex';
        fillResult('r_', result);
        stopLoadingRecord(true);
    } catch (err) {
        console.error(err);
        alert('Gagal menganalisis rekaman: ' + err.message);
    }
}

function cancelRecording() {
    recordedWavBlob = null;
    document.getElementById('recordPlayerSection').classList.add('hidden');
    document.getElementById('recordTimer').textContent = '00:00';
}

/* ===================== UPLOAD FILE ===================== */
function handleFileSelect(e) {
    const file = e.target.files[0];
    if (!file) return;

    const maxSize = 500 * 1024 * 1024; // 500MB

    if (file.size > maxSize) {
        alert("Ukuran file terlalu besar! Maksimal 500MB.");
        e.target.value = ""; 
        return;
    }

    // Tampilkan nama file
    const fileNameDiv = document.getElementById("fileNameDisplay");
    fileNameDiv.textContent = "" + file.name;
    fileNameDiv.style.display = "block";

    // Tampilkan audio player
    const audioUrl = URL.createObjectURL(file);
    const player = document.getElementById('uploadedAudio');
    player.src = audioUrl;

    document.getElementById('audioPlayerSection').style.display = 'block';
}

function cancelUpload() {
  document.getElementById('fileInput').value = '';
  document.getElementById('audioPlayerSection').style.display = 'none';
  document.getElementById('uploadResultSection').style.display = 'none';
}

async function analyzeAudio() {
  const file = document.getElementById('fileInput').files[0];
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
    document.getElementById('uploadResultSection').style.display = 'flex';
    fillResult('u_', result);
    stopLoadingUpload(true);
  } catch (err) {
    console.error(err);
    alert('Gagal menganalisis audio: ' + err.message);
  }
}

/* ===================== RENDER HASIL ===================== */
function fillResult(prefix, result) {
  const dist = result.distribution_by_emotion || {};
  const sukuDist = result.distribution_by_suku || {};
  const emotions = ["Happy", "Sad", "Angry", "Surprised", "Neutral"];

  /* ===================== EMOSI ===================== */
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

  document.getElementById(prefix + 'emojiIcon').src = emojiMap[dominantEmotion];
  document.getElementById(prefix + 'mainEmotion').innerText = dominantEmotion;
  document.getElementById(prefix + 'mainPercent').innerText = maxEmotionVal.toFixed(2) + '%';

  /* ===================== SUKU ===================== */
  let dominantSuku = "-";
  let maxSukuVal = 0;

  const sukuKeys = Object.keys(sukuDist);
  const barChart = document.getElementById(prefix + 'sukuBars');

  sukuKeys.forEach(suku => {
    const val = parseFloat(sukuDist[suku]?.percent ?? 0);

    if (val > maxSukuVal) {
      maxSukuVal = val;
      dominantSuku = suku;
    }

    // Buat elemen bar suku kalau belum ada
    let barEl = document.getElementById(prefix + 'sukuBar_' + suku);
    if (!barEl) {
      const label = document.createElement('div');
      label.classList.add('bar-label');
      label.innerHTML = `${suku} <span id="${prefix}sukuVal_${suku}">0%</span>`;
      barChart.appendChild(label);

      barEl = document.createElement('div');
      barEl.classList.add('bar', 'suku');
      barEl.id = prefix + 'sukuBar_' + suku;
      barChart.appendChild(barEl);
    }

    barEl.style.width = val + '%';

    const spanVal = document.getElementById(prefix + 'sukuVal_' + suku);
    if (spanVal) spanVal.innerText = val.toFixed(2) + '%';
  });

  document.getElementById(prefix + 'sukuDominant').innerText = dominantSuku;
  document.getElementById(prefix + 'sukuDominantPercent').innerText = maxSukuVal.toFixed(2) + '%';

  const sukuEmojiMap = {
    "Jawa": "./img/jawa.png",
    "Sunda": "./img/sunda.png",
    "Batak": "./img/batak.png",
    "Minang": "./img/minang.png",
    "Betawi": "./img/betawi.png",
    "-": "./img/neutral.png"
  };

  const sukuEmojiEl = document.getElementById(prefix + 'sukuEmojiIcon');
  if (sukuEmojiEl) {
    sukuEmojiEl.src = sukuEmojiMap[dominantSuku] || "./img/neutral.png";
  }
}

async function generatePDF() {
    const { jsPDF } = window.jspdf;

    const doc = new jsPDF({
        orientation: "portrait",
        unit: "mm",
        format: "a4"
    });

    let y = 20; // posisi tulisan dari atas

    // ====== 1. HEADER SURAT ======
    doc.setFontSize(16);
    doc.text("LAPORAN HASIL ANALISIS SUARA", 105, y, { align: "center" });
    y += 10;

    doc.setFontSize(12);
    doc.text("Speech Emotion Recognition (SER) - Aplikasi SuaraKu", 105, y, { align: "center" });
    y += 15;

    // ====== 2. INFORMASI PENGGUNA ======
    const nama = document.getElementById("inputNama").value || "-";
    const email = document.getElementById("inputEmail").value || "-";
    const gender = document.getElementById("inputGender").value || "-";
    const usia = document.getElementById("inputUsia").value || "-";

    doc.setFontSize(13);
    doc.text("Informasi Pengguna", 14, y);
    y += 8;

    doc.setFontSize(11);
    doc.text(`Nama             : ${nama}`, 14, y); y += 7;
    doc.text(`Email            : ${email}`, 14, y); y += 7;
    doc.text(`Jenis Kelamin    : ${gender}`, 14, y); y += 7;
    doc.text(`Usia             : ${usia}`, 14, y); y += 12;

    // ====== 3. HASIL ANALISIS EMOSI ======
    const emo_happy = document.getElementById("u_happyVal").innerText;
    const emo_sad = document.getElementById("u_sadVal").innerText;
    const emo_angry = document.getElementById("u_angryVal").innerText;
    const emo_surprised = document.getElementById("u_surprisedVal").innerText;
    const emo_neutral = document.getElementById("u_neutralVal").innerText;

    const mainEmotion = document.getElementById("u_mainEmotion").innerText;
    const mainPercent = document.getElementById("u_mainPercent").innerText;

    doc.setFontSize(13);
    doc.text("Hasil Analisis Emosi", 14, y);
    y += 8;

    doc.setFontSize(11);
    doc.text(`Happy      : ${emo_happy}`, 14, y); y += 6;
    doc.text(`Sad        : ${emo_sad}`, 14, y); y += 6;
    doc.text(`Angry      : ${emo_angry}`, 14, y); y += 6;
    doc.text(`Surprised  : ${emo_surprised}`, 14, y); y += 6;
    doc.text(`Neutral    : ${emo_neutral}`, 14, y); y += 10;

    doc.setFontSize(12);
    doc.text(`Emosi Dominan : ${mainEmotion} (${mainPercent})`, 14, y);
    y += 15;

    // ====== 4. HASIL ANALISIS SUKU ======
    const sukuDominant = document.getElementById("u_sukuDominant").innerText;
    const sukuPercent = document.getElementById("u_sukuDominantPercent").innerText;

    doc.setFontSize(13);
    doc.text("Hasil Analisis Suku", 14, y);
    y += 8;

    doc.setFontSize(11);

    const sukuBars = document.querySelectorAll("#u_sukuBars .bar-label");
    sukuBars.forEach(b => {
        doc.text(b.innerText, 14, y);
        y += 6;
    });

    y += 5;
    doc.setFontSize(12);
    doc.text(`Suku Dominan : ${sukuDominant} (${sukuPercent})`, 14, y);

    // ====== 5. FOOTER ======
    y += 30;
    doc.setFontSize(10);
    doc.text("Dokumen ini dibuat otomatis oleh sistem SER SuaraKu.", 105, y, { align: "center" });

    // SIMPAN PDF
    doc.save(`Hasil_Analisis_Suara.pdf`);
}

/* ===================== SCROLL ===================== */
function scrollToSection(id) {
    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</body>
</html>
