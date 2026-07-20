<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Check-in - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-900 min-h-screen text-white p-6">

    <div class="max-w-md mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-black">Scanner Check-in</h1>
            <p class="text-slate-400 text-sm mt-1">Arahkan kamera ke QR code tiket peserta.</p>
        </div>

        <div id="reader" class="rounded-3xl overflow-hidden border-4 border-slate-700"></div>

        <div id="result" class="mt-6 hidden rounded-3xl p-6 text-center">
            <p id="result-icon" class="text-5xl mb-3"></p>
            <p id="result-message" class="font-bold text-lg mb-1"></p>
            <div id="result-detail" class="text-sm text-slate-300 space-y-1"></div>
            <button onclick="resumeScanning()" class="mt-4 px-6 py-3 bg-indigo-600 rounded-2xl font-bold text-sm">
                Scan Berikutnya
            </button>
        </div>

        <p class="text-center text-slate-500 text-xs mt-8">
            Total check-in valid pada sesi ini: <span id="scan-count" class="font-bold text-white">0</span>
        </p>
    </div>

    <script>
        const verifyUrl = @json($verifyUrl);
        const csrfToken = @json(csrf_token());
        let scanCount = 0;
        let isProcessing = false;

        const html5QrCode = new Html5Qrcode("reader");

        function startScanning() {
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            ).catch(err => {
                document.getElementById('reader').innerHTML =
                    '<p class="p-6 text-rose-400 text-sm">Gagal mengakses kamera: ' + err + '</p>';
            });
        }

        async function onScanSuccess(decodedText) {
            if (isProcessing) return;
            isProcessing = true;

            await html5QrCode.pause(true);

            try {
                const res = await fetch(verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ order_id: decodedText }),
                });
                const data = await res.json();
                showResult(data);
            } catch (e) {
                showResult({ valid: false, message: 'Gagal menghubungi server.' });
            }
        }

        function showResult(data) {
            const box = document.getElementById('result');
            const icon = document.getElementById('result-icon');
            const msg = document.getElementById('result-message');
            const detail = document.getElementById('result-detail');

            box.classList.remove('hidden', 'bg-green-900/40', 'bg-rose-900/40');
            box.classList.add(data.valid ? 'bg-green-900/40' : 'bg-rose-900/40');

            icon.textContent = data.valid ? '✅' : '❌';
            msg.textContent = data.message;
            detail.innerHTML = '';

            if (data.valid && data.data) {
                scanCount++;
                document.getElementById('scan-count').textContent = scanCount;
                detail.innerHTML = `
                    <p><span class="text-slate-400">Nama:</span> ${data.data.name}</p>
                    <p><span class="text-slate-400">Event:</span> ${data.data.event}</p>
                    <p><span class="text-slate-400">Order ID:</span> ${data.data.order_id}</p>
                `;
            }

            document.getElementById('reader').classList.add('hidden');
            box.classList.remove('hidden');
        }

        function resumeScanning() {
            document.getElementById('result').classList.add('hidden');
            document.getElementById('reader').classList.remove('hidden');
            isProcessing = false;
            html5QrCode.resume();
        }

        startScanning();
    </script>
</body>
</html>