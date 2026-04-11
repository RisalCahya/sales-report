@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="app-soft-card mb-6 rounded-[28px] px-6 py-6">
            <h1 class="app-accent-title text-3xl font-extrabold">Buat Laporan Baru</h1>
            <p class="mt-2 text-sm text-slate-600">Tanggal: <strong>{{ now()->format('l, d F Y') }}</strong></p>
        </div>

        <!-- Main Form -->
        <form id="reportForm" method="POST" action="{{ route('reports.store') }}" class="space-y-6">
            @csrf

            <!-- Kunjungan Container -->
            <div id="kunjunganContainer" class="space-y-4">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Errors -->
            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-red-800 mb-2">Ada beberapa kesalahan:</h3>
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add Kunjungan Button -->
            <button type="button" id="addKunjunganBtn" class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white font-semibold rounded-2xl hover:opacity-95 transition-colors flex items-center justify-center text-lg shadow-lg shadow-emerald-100">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kunjungan
            </button>

            <!-- Submit Button -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit" id="submitBtn" class="app-primary-gradient w-full sm:flex-1 px-6 py-3 text-white font-semibold text-lg rounded-2xl transition-colors flex items-center justify-center shadow-lg shadow-sky-100">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="submitBtnText">Simpan Laporan</span>
                    <span id="loadingSpinner" class="hidden ml-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
                <a href="{{ route('reports.index') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-white/85 border border-sky-100 text-slate-800 font-semibold rounded-2xl hover:bg-white transition-colors shadow-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Kunjungan Template -->
<template id="kunjunganTemplate">
    <div class="kunjungan-card app-soft-card rounded-[28px] border border-white/70 p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Kunjungan <span class="kunjungan-number text-blue-600"></span></h3>
            <button type="button" class="removeKunjunganBtn text-red-600 hover:text-red-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <input type="hidden" class="kunjungan-index" value="0">

        <!-- Outlet -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Nama Outlet <span class="text-red-600">*</span>
            </label>
            <input type="text" name="kunjungan[0][outlet]" class="kunjungan-outlet w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Toko ABC" required>
        </div>

        <!-- Alamat -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Alamat <span class="text-red-600">*</span>
            </label>
            <textarea name="kunjungan[0][alamat]" class="kunjungan-alamat w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Masukkan alamat lengkap" required></textarea>
        </div>

        <!-- PIC -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Nama PIC (Person In Charge) <span class="text-red-600">*</span>
            </label>
            <input type="text" name="kunjungan[0][pic]" class="kunjungan-pic w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Budi Santoso" required>
        </div>

        <!-- Keterangan -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
            <textarea name="kunjungan[0][keterangan]" class="kunjungan-keterangan w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
        </div>

        <!-- Camera Section -->
        <div class="mb-4 rounded-2xl bg-gradient-to-br from-sky-50 via-white to-fuchsia-50 p-4">
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Foto Bukti Kunjungan <span class="text-red-600">*</span>
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Camera -->
                <div class="flex flex-col">
                    <div class="mb-3 hidden cameraDeviceWrap">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Kamera</label>
                        <select class="cameraDeviceSelect w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Kamera default</option>
                        </select>
                    </div>

                    <div class="mb-3 flex gap-2">
                        <button type="button" class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors cameraTriggerBtn flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ambil Foto
                        </button>
                        <input type="file" class="cameraInput hidden" accept="image/*" capture="environment">
                    </div>

                    <video class="cameraVideo hidden w-full bg-black rounded-lg" playsinline autoplay muted></video>
                    <canvas class="cameraCanvas hidden w-full bg-gray-800 rounded-lg" style="max-height: 300px;"></canvas>

                    <div class="flex gap-2 mt-2">
                        <button type="button" class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors captureBtn hidden text-sm">
                            Capture
                        </button>
                        <button type="button" class="flex-1 px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors closeCameraBtn hidden text-sm">
                            Tutup
                        </button>
                    </div>
                </div>

                <!-- Preview -->
                <div class="flex flex-col">
                    <div class="flex-1 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
                        <img class="fotoPreview hidden w-full h-full object-cover" alt="Preview">
                        <div class="fotoPlaceholder text-gray-500 text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Preview foto
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="kunjungan[0][foto]" class="kunjungan-foto" required>
            <input type="hidden" name="kunjungan[0][captured_at_label]" class="kunjungan-captured-at-label">
        </div>

        <!-- GPS Section -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-purple-50 via-white to-fuchsia-50 border border-purple-100">
            <div class="flex justify-between items-center mb-3">
                <label class="block text-sm font-medium text-gray-700">Lokasi GPS <span class="text-red-600">*</span></label>
                <button type="button" class="getGpsBtn px-3 py-1 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Ambil Lokasi
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-600">Latitude</label>
                    <input type="text" name="kunjungan[0][latitude]" class="kunjungan-latitude w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly placeholder="Menunggu lokasi...">
                </div>
                <div>
                    <label class="text-xs text-gray-600">Longitude</label>
                    <input type="text" name="kunjungan[0][longitude]" class="kunjungan-longitude w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly placeholder="Menunggu lokasi...">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let kunjunganCount = 0;
    const container = document.getElementById('kunjunganContainer');
    const template = document.getElementById('kunjunganTemplate');
    const addBtn = document.getElementById('addKunjunganBtn');
    const reportForm = document.getElementById('reportForm');
    const afterSubmitRedirectKey = 'report_form_submitted_redirect';

    // Prevent returning to stale filled form when user navigates back after successful submit.
    window.addEventListener('pageshow', function(event) {
        const navEntry = performance.getEntriesByType('navigation')[0];
        const isBackForward = event.persisted || (navEntry && navEntry.type === 'back_forward');

        if (isBackForward && sessionStorage.getItem(afterSubmitRedirectKey) === '1') {
            sessionStorage.removeItem(afterSubmitRedirectKey);
            window.location.replace('{{ route('reports.index') }}');
        }
    });

    // Add initial kunjungan
    addKunjungan();

    // Add Kunjungan Button
    addBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addKunjungan();
    });

    function addKunjungan() {
        const clone = template.content.cloneNode(true);
        const index = kunjunganCount++;
        const cardElement = clone.querySelector('.kunjungan-card');

        // Update index-related elements
        clone.querySelector('.kunjungan-number').textContent = index + 1;
        clone.querySelector('.kunjungan-index').value = index;

        // Update all name attributes
        clone.querySelectorAll('input, textarea').forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/g, `[${index}]`);
            }
        });

        // Setup event listeners on actual card element
        setupKunjunganEvents(cardElement, index);

        container.appendChild(clone);
    }

    function setupKunjunganEvents(element, index) {
        const removeBtn = element.querySelector('.removeKunjunganBtn');
        const cameraTriggerBtn = element.querySelector('.cameraTriggerBtn');
        const cameraInput = element.querySelector('.cameraInput');
        const cameraDeviceWrap = element.querySelector('.cameraDeviceWrap');
        const cameraDeviceSelect = element.querySelector('.cameraDeviceSelect');
        const cameraVideo = element.querySelector('.cameraVideo');
        const cameraCanvas = element.querySelector('.cameraCanvas');
        const captureBtn = element.querySelector('.captureBtn');
        const closeCameraBtn = element.querySelector('.closeCameraBtn');
        const fotoInput = element.querySelector('.kunjungan-foto');
        const capturedAtLabelInput = element.querySelector('.kunjungan-captured-at-label');
        const fotoPreview = element.querySelector('.fotoPreview');
        const fotoPlaceholder = element.querySelector('.fotoPlaceholder');
        const getGpsBtn = element.querySelector('.getGpsBtn');
        const latitudeInput = element.querySelector('.kunjungan-latitude');
        const longitudeInput = element.querySelector('.kunjungan-longitude');

        let stream = null;
        const canvasCtx = cameraCanvas.getContext('2d');

        function stopCameraStream() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            cameraVideo.srcObject = null;
        }

        async function populateCameraOptions() {
            if (!(navigator.mediaDevices && navigator.mediaDevices.enumerateDevices)) {
                return;
            }

            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(device => device.kind === 'videoinput');

            cameraDeviceSelect.innerHTML = '<option value="">Kamera default</option>';

            videoDevices.forEach((device, idx) => {
                const option = document.createElement('option');
                option.value = device.deviceId;

                const fallbackName = idx === 0 ? 'Kamera 1 (umumnya depan)' : 'Kamera ' + (idx + 1);
                option.textContent = device.label || fallbackName;
                cameraDeviceSelect.appendChild(option);
            });

            cameraDeviceWrap.classList.toggle('hidden', videoDevices.length <= 1);
        }

        async function startCamera(deviceId = null) {
            stopCameraStream();

            const videoConstraints = deviceId
                ? { deviceId: { exact: deviceId } }
                : {
                    facingMode: { ideal: 'environment' },
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                };

            stream = await navigator.mediaDevices.getUserMedia({
                video: videoConstraints,
                audio: false,
            });

            cameraVideo.srcObject = stream;
            await cameraVideo.play();

            cameraVideo.classList.remove('hidden');
            cameraCanvas.classList.add('hidden');
            cameraTriggerBtn.classList.add('hidden');
            captureBtn.classList.remove('hidden');
            closeCameraBtn.classList.remove('hidden');

            await populateCameraOptions();
        }

        function showCapturedPreview(dataUrl, timeString) {
            fotoInput.value = dataUrl;
            capturedAtLabelInput.value = timeString;
            fotoPreview.src = dataUrl;
            fotoPreview.classList.remove('hidden');
            fotoPlaceholder.classList.add('hidden');
        }

        function formatJakartaDateTimeAMPM(dateObj) {
            return dateObj.toLocaleString('id-ID', {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            });
        }

        // Remove button
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (container.querySelectorAll('.kunjungan-card').length > 1) {
                element.remove();
                updateKunjunganNumbers();
            } else {
                alert('Minimal harus ada 1 kunjungan');
            }
        });

        // Camera trigger
        cameraTriggerBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            try {
                const supportsCamera = navigator.mediaDevices && typeof navigator.mediaDevices.getUserMedia === 'function';
                if (!supportsCamera) {
                    cameraInput.click();
                    return;
                }

                await startCamera(cameraDeviceSelect.value || null);
            } catch (err) {
                cameraInput.click();
            }
        });

        cameraDeviceSelect.addEventListener('change', async function() {
            if (!stream) {
                return;
            }

            try {
                await startCamera(cameraDeviceSelect.value || null);
            } catch (err) {
                alert('Gagal mengganti kamera: ' + err.message);
            }
        });

        // File input fallback for browsers/devices where live camera stream is not available.
        cameraInput.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function(loadEvent) {
                const now = new Date();
                const timeString = formatJakartaDateTimeAMPM(now);

                showCapturedPreview(loadEvent.target.result, timeString);
                cameraVideo.classList.add('hidden');
                cameraCanvas.classList.add('hidden');
                captureBtn.classList.add('hidden');
                closeCameraBtn.classList.add('hidden');
                cameraTriggerBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            cameraInput.value = '';
        });

        // Capture button
        captureBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const width = cameraVideo.videoWidth;
            const height = cameraVideo.videoHeight;

            if (!width || !height) {
                alert('Kamera belum siap. Tunggu sebentar lalu coba Capture lagi.');
                return;
            }

            cameraCanvas.width = width;
            cameraCanvas.height = height;

            // Draw video frame
            canvasCtx.drawImage(cameraVideo, 0, 0, width, height);

            // Add timestamp
            const now = new Date();
            const timeString = formatJakartaDateTimeAMPM(now);

            // Configuration for timestamp
            const padding = 20;
            const fontSize = Math.floor(height * 0.05);
            const fontSizeSmall = fontSize * 0.7;

            // Draw semi-transparent background for text
            canvasCtx.fillStyle = 'rgba(0, 0, 0, 0.7)';
            canvasCtx.fillRect(padding - 10, height - fontSize * 3 - 20, 400, fontSize * 3 + 10);

            // Draw timestamp text
            canvasCtx.font = `bold ${fontSize}px Arial`;
            canvasCtx.fillStyle = 'white';
            canvasCtx.fillText(timeString, padding, height - fontSize * 2);

            // Save and show preview
            showCapturedPreview(cameraCanvas.toDataURL('image/png'), timeString);

            // Hide video and buttons
            cameraVideo.classList.add('hidden');
            cameraCanvas.classList.add('hidden');
            captureBtn.classList.add('hidden');
            closeCameraBtn.classList.add('hidden');
            cameraTriggerBtn.classList.remove('hidden');

            // Stop stream
            stopCameraStream();
        });

        // Close camera
        closeCameraBtn.addEventListener('click', function(e) {
            e.preventDefault();
            cameraVideo.classList.add('hidden');
            cameraCanvas.classList.add('hidden');
            captureBtn.classList.add('hidden');
            closeCameraBtn.classList.add('hidden');
            cameraTriggerBtn.classList.remove('hidden');

            stopCameraStream();
        });

        // GPS button
        getGpsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            getGpsBtn.disabled = true;
            getGpsBtn.textContent = 'Mengambil lokasi...';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        latitudeInput.value = position.coords.latitude.toFixed(8);
                        longitudeInput.value = position.coords.longitude.toFixed(8);
                        getGpsBtn.disabled = false;
                        getGpsBtn.innerHTML = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Ambil Lokasi';

                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'p-2 bg-green-100 text-green-700 text-sm rounded mt-2';
                        successMsg.textContent = '✓ Lokasi berhasil diambil';
                        getGpsBtn.parentElement.appendChild(successMsg);
                        setTimeout(() => successMsg.remove(), 3000);
                    },
                    function(error) {
                        alert('Gagal mengambil lokasi: ' + error.message);
                        getGpsBtn.disabled = false;
                        getGpsBtn.textContent = 'Ambil Lokasi';
                    }
                );
            } else {
                alert('Geolocation tidak tersedia di browser Anda');
                getGpsBtn.disabled = false;
                getGpsBtn.textContent = 'Ambil Lokasi';
            }
        });
    }

    function updateKunjunganNumbers() {
        const cards = container.querySelectorAll('.kunjungan-card');
        cards.forEach((card, idx) => {
            card.querySelector('.kunjungan-number').textContent = idx + 1;
        });
    }

    // Form submit
    reportForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate all kunjungan have photos and GPS
        const kunjunganCards = container.querySelectorAll('.kunjungan-card');
        for (let i = 0; i < kunjunganCards.length; i++) {
            const card = kunjunganCards[i];
            const num = i + 1;

            const fotoInput = card.querySelector('.kunjungan-foto');
            if (!fotoInput.value) {
                alert('Kunjungan ' + num + ': Foto bukti kunjungan wajib dilampirkan. Mohon ambil foto terlebih dahulu.');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const latInput = card.querySelector('.kunjungan-latitude');
            const lngInput = card.querySelector('.kunjungan-longitude');
            if (!latInput.value || !lngInput.value) {
                alert('Kunjungan ' + num + ': Lokasi GPS wajib dilampirkan. Mohon tekan tombol "Ambil Lokasi" terlebih dahulu.');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
        }

        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        submitBtn.disabled = true;
        submitBtnText.textContent = 'Menyimpan...';
        loadingSpinner.classList.remove('hidden');
            sessionStorage.setItem(afterSubmitRedirectKey, '1');

        // Submit form
        setTimeout(() => {
            this.submit();
        }, 100);
    });
});
</script>

<style>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endsection
