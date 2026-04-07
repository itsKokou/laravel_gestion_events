@extends('layouts.scanner')

@section('title', 'Scanner · ' . $event->name)

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('scanner.home') }}"
            class="mb-3 inline-flex text-sm font-semibold text-orange-50/90 no-underline hover:text-white">
            ← Toutes les soirées
        </a>
        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-orange-50/75">Scanner</p>
        <h1 class="mb-3 text-3xl font-black leading-tight tracking-tight text-white sm:text-[28px]">
            {{ $event->name }}
        </h1>
        <p class="mb-4 text-sm text-orange-50/85">
            {{ $event->starts_at->format('d/m/Y H:i') }} · {{ $event->venue_name }}
        </p>
        
        <!-- Statistiques mobiles -->
        <div class="card" style="padding: 16px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Présents
            </div>
                <div style="display: flex; align-items: baseline; gap: 6px;">
                    <span id="presentCount" style="font-weight: 900; font-size: 24px; color: var(--we-primary);">
                        {{ $presentCount }}
                    </span>
                    <span class="muted" style="font-size: 16px;">/ {{ $event->capacity }}</span>
                </div>
            </div>
            @if($event->capacity)
                <div style="height: 8px; background: var(--we-primary-soft); border-radius: 999px; overflow: hidden; margin-bottom: 6px;">
                    <div id="capacityBarMobile" style="height: 100%; background: linear-gradient(90deg, var(--we-gradient-start), var(--we-gradient-end)); border-radius: 999px; transition: width 0.3s ease; width: {{ $event->capacity > 0 ? ($presentCount / $event->capacity) * 100 : 0 }}%;"></div>
                </div>
                <div style="font-size: 11px; color: var(--we-muted); text-align: center;">
                    @php($percentage = $event->capacity > 0 ? round(($presentCount / $event->capacity) * 100) : 0)
                    {{ $percentage }}% de capacité
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid2" style="gap: 24px; margin-bottom: 24px;">
        <!-- Zone de scan -->
        <div style="flex: 1;">
            <!-- Scanner QR Code -->
            <div class="card" style="padding: 24px; margin-bottom: 24px;">
                <h2 style="font-size: 20px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.3px;">📱 Scanner QR Code</h2>
                
                <!-- Zone du scanner -->
                <div id="scannerSection" style="margin-bottom: 20px;">
                    <div id="cameraErrorBanner" class="hidden mb-4 rounded-xl border border-red-500/40 bg-red-950/50 px-4 py-3 text-sm text-red-200" role="alert"></div>
                    <div id="cameraContainer" style="display: none;">
                        <div style="position: relative; width: 100%; max-width: 100%; margin: 0 auto; border-radius: 16px; overflow: hidden; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                            <div id="qr-reader" style="width: 100%; position: relative;"></div>
                        </div>
                        <div style="text-align: center; margin-top: 16px;">
                            <div style="font-size: 13px; color: var(--we-text); font-weight: 600; margin-bottom: 12px;">
                                Pointez la caméra vers le QR code du billet
                            </div>
                            <button id="stopCameraBtn" class="btn border border-red-600 bg-red-600 text-white shadow-sunset hover:shadow-lg hover:-translate-y-0.5 hover:bg-red-700 hover:to-red-800" type="button" style="padding: 12px 20px; font-size: 14px; width: 100%; max-width: 300px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                                </svg>
                                Arrêter le scanner
                            </button>
                        </div>
                    </div>

                    <div id="scannerPlaceholder" style="padding: 40px 24px; background: linear-gradient(135deg, var(--we-primary-soft), rgba(255, 255, 255, 0.5)); border-radius: 16px; border: 2px dashed color-mix(in srgb, var(--we-primary) 35%, transparent); text-align: center;">
                        <div style="font-size: 48px; margin-bottom: 12px;">📷</div>
                        <div style="font-size: 18px; font-weight: 700; color: var(--we-text); margin-bottom: 8px;">
                            Scanner un billet
                        </div>
                        <div style="font-size: 13px; color: var(--we-muted); margin-bottom: 20px; line-height: 1.5;">
                            Activez votre caméra pour scanner automatiquement les QR codes des billets
                        </div>
                        <button id="startScannerBtn" class="btn text-sm border border-orange-600 bg-orange-600 text-white shadow-sunset hover:shadow-lg hover:-translate-y-0.5 hover:bg-orange-700 hover:to-orange-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                            </svg>
                            Démarrer le scanner
                        </button>
                    </div>
                </div>

                <!-- Saisie manuelle (optionnel) -->
                <div style="padding-top: 20px; border-top: 2px solid var(--we-border);">
                    <div style="font-size: 11px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; text-align: center;">
                        Ou saisie manuelle
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <input id="qr_token" 
                            placeholder="Collez le token QR ici…" 
                            autocomplete="off"
                            style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 2px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px; font-family: ui-monospace, monospace;"
                            onfocus="this.style.borderColor='color-mix(in srgb, var(--we-primary) 55%, #fff)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        <button class="btn" id="scanBtn" type="button" style="padding: 14px 24px; font-size: 14px; width: 100%;">
                            Scanner
                        </button>
                    </div>
                </div>
        </div>

            <!-- Pop-up de résultat du scan -->
            <div id="resultModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 16px; overflow-y: auto;">
                <div class="card" style="max-width: 500px; width: 100%; padding: 24px; position: relative; animation: slideIn 0.3s ease-out; margin: auto;">
                    <button id="closeModalBtn" style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.05); border: none; font-size: 28px; color: #8b7355; cursor: pointer; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: background 120ms ease; z-index: 10;" 
                            onmouseover="this.style.background='rgba(0,0,0,0.1)'" 
                            onmouseout="this.style.background='rgba(0,0,0,0.05)'"
                            ontouchstart="this.style.background='rgba(0,0,0,0.15)'"
                            ontouchend="this.style.background='rgba(0,0,0,0.05)'">
                        ×
                    </button>
                    <div id="resultContent"></div>
                </div>
            </div>

            <style>
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(-20px) scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
            </style>
        </div>

        <!-- Statistiques (masquées sur mobile) -->
        <div style="flex: 0 0 380px;" class="stats-sidebar">
            <div class="card" style="padding: 24px;">
                <h3 style="font-size: 18px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.3px;">Statistiques</h3>
                
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Taux de remplissage
                        </div>
                        <div style="height: 12px; background: #f1f5f9; border-radius: 999px; overflow: hidden;">
                            <div id="capacityBar" style="height: 100%; background: linear-gradient(90deg, var(--we-gradient-start), var(--we-gradient-end)); border-radius: 999px; transition: width 0.3s ease; width: {{ $event->capacity > 0 ? ($presentCount / $event->capacity) * 100 : 0 }}%;"></div>
                        </div>
                        <div style="font-size: 14px; color: var(--we-text); margin-top: 8px;">
                            <span id="presentCountStat">{{ $presentCount }}</span> personnes présentes
                        </div>
                    </div>

                    <div style="padding-top: 20px; border-top: 2px solid var(--we-border);">
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Places restantes
                        </div>
                        <div style="font-size: 28px; font-weight: 900; color: var(--we-text);">
                            <span id="remainingCount">{{ max(0, $event->capacity - $presentCount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bibliothèque HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <style>
        /* Styles pour l'aperçu vidéo du scanner */
        #qr-reader {
            position: relative;
            width: 100%;
            min-height: 300px;
        }
        
        #qr-reader video {
            width: 100% !important;
            height: auto !important;
            display: block;
            border-radius: 16px;
            object-fit: cover;
        }
        
        #qr-reader__dashboard {
            display: none !important;
        }
        
        #qr-reader__scan_region {
            position: relative;
        }
        
        #qr-reader__camera_selection {
            display: none;
        }
        
        /* Overlay personnalisé pour le guide de scan */
        #qr-reader__scan_region::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: min(250px, 80vw);
            height: min(250px, 80vw);
            border: 3px solid var(--we-primary);
            border-radius: 12px;
            pointer-events: none;
            z-index: 10;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
        }

        /* Responsive pour mobile */
        @media (max-width: 768px) {
            .grid2 {
                grid-template-columns: 1fr !important;
            }
            
            .stats-sidebar {
                display: none !important;
            }
            
            h1 {
                font-size: 24px !important;
            }
            
            .card {
                padding: 20px 16px !important;
            }
            
            #qr-reader {
                min-height: 250px !important;
            }
            
            #resultModal {
                padding: 12px !important;
                align-items: flex-start !important;
                padding-top: 20px !important;
            }
            
            #resultModal .card {
                padding: 20px 16px !important;
                max-width: 100% !important;
            }
            
            #resultModal #resultContent > div:first-child {
                flex-direction: column !important;
                gap: 12px !important;
            }
            
            #resultModal #resultContent > div:first-child > div:first-child {
                width: 48px !important;
                height: 48px !important;
                font-size: 24px !important;
            }
            
            #resultModal #resultContent > div:first-child > div:last-child > div:first-child {
                font-size: 20px !important;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 16px !important;
            }
            
            h1 {
                font-size: 22px !important;
            }
            
            #qr-reader {
                min-height: 200px !important;
            }
            
            #scannerPlaceholder {
                padding: 32px 20px !important;
            }
            
            #scannerPlaceholder > div:first-child {
                font-size: 48px !important;
            }
            
            #scannerPlaceholder > div:nth-child(2) {
                font-size: 18px !important;
            }
            
            #resultModal {
                padding: 8px !important;
            }
            
            #resultModal .card {
                padding: 16px 12px !important;
            }
        }
        
        /* Mode paysage mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            #qr-reader {
                min-height: 300px !important;
            }
        }
    </style>

    <script>
        const btn = document.getElementById('scanBtn');
        const input = document.getElementById('qr_token');
        const modal = document.getElementById('resultModal');
        const resultContent = document.getElementById('resultContent');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const presentCount = document.getElementById('presentCount');
        const presentCountStat = document.getElementById('presentCountStat');
        const remainingCount = document.getElementById('remainingCount');
        const capacityBar = document.getElementById('capacityBar');
        const capacityBarMobile = document.getElementById('capacityBarMobile');
        const totalCapacity = {{ $event->capacity }};
        const startScannerBtn = document.getElementById('startScannerBtn');
        const stopCameraBtn = document.getElementById('stopCameraBtn');
        const cameraContainer = document.getElementById('cameraContainer');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        let html5QrCode = null;
        let isScanning = false;

        function updateStats(newCount) {
            presentCount.textContent = newCount;
            presentCountStat.textContent = newCount;
            remainingCount.textContent = Math.max(0, totalCapacity - newCount);
            const percentage = totalCapacity > 0 ? (newCount / totalCapacity) * 100 : 0;
            if (capacityBar) capacityBar.style.width = percentage + '%';
            if (capacityBarMobile) capacityBarMobile.style.width = percentage + '%';
        }

        function showResult(result, message, extra = {}) {
            modal.style.display = 'flex';
            
            const configs = {
                valid: {
                    icon: '✅',
                    title: 'Billet valide',
                    bg: 'rgba(34, 197, 94, 0.1)',
                    border: 'rgba(34, 197, 94, 0.3)',
                    titleColor: '#16a34a',
                    messageColor: '#15803d'
                },
                already_used: {
                    icon: '⚠️',
                    title: 'Billet déjà scanné',
                    bg: 'rgba(251, 191, 36, 0.1)',
                    border: 'rgba(251, 191, 36, 0.3)',
                    titleColor: '#d97706',
                    messageColor: '#b45309'
                },
                invalid: {
                    icon: '❌',
                    title: 'Billet invalide',
                    bg: 'rgba(239, 68, 68, 0.1)',
                    border: 'rgba(239, 68, 68, 0.3)',
                    titleColor: '#dc2626',
                    messageColor: '#b91c1c'
                }
            };

            const config = configs[result] || configs.invalid;

            let html = `
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid var(--we-border);">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: ${config.bg}; display: flex; align-items: center; justify-content: center; font-size: 32px; flex-shrink: 0;">
                        ${config.icon}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 24px; font-weight: 900; color: ${config.titleColor}; margin-bottom: 4px;">
                            ${config.title}
                        </div>
                        <div style="font-size: 16px; color: ${config.messageColor}; font-weight: 600;">
                            ${message || ''}
                        </div>
                    </div>
                </div>
            `;

            if (extra.attendee) {
                html += `
                    <div style="padding: 16px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border); margin-bottom: 12px;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Participant
                        </div>
                        <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 4px;">
                            ${extra.attendee.name}
                        </div>
                        <div style="font-size: 14px; color: var(--we-muted);">
                            ${extra.attendee.email}
                        </div>
                    </div>
                `;
            }

            if (extra.checked_in_at) {
                html += `
                    <div style="padding: 16px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Premier scan
                        </div>
                        <div style="font-size: 14px; color: var(--we-text);">
                            ${new Date(extra.checked_in_at).toLocaleString('fr-FR', { 
                                day: '2-digit', 
                                month: '2-digit', 
                                year: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit',
                                second: '2-digit'
                            })}
                        </div>
                    </div>
                `;
            }

            resultContent.innerHTML = html;
            input.value = '';
            
            // Ne pas remettre le focus sur l'input si le scanner est actif
            if (!isScanning) {
                input.focus();
            }
        }

        // Fermer la modale
        function closeModal() {
            modal.style.display = 'none';
            
            // Reprendre le scanner si actif
            if (html5QrCode && isScanning) {
                try {
                    html5QrCode.resume();
                } catch (e) {
                    // Si resume échoue, ignorer
                }
            }
            
            if (!isScanning) {
                input.focus();
            }
        }

        closeModalBtn.addEventListener('click', closeModal);
        
        // Fermer en cliquant sur l'overlay
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });

        async function doScan() {
            const token = input.value.trim();
            if (!token) {
                input.focus();
                return Promise.resolve();
            }

            btn.disabled = true;
            btn.textContent = 'Scan en cours...';
            
            try {
                const res = await fetch(@json(route('scanner.scan', $event)), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ qr_token: token }),
                });
                
                const data = await res.json().catch(() => ({}));
                
                showResult(data.result || 'invalid', data.message || 'Erreur', data);
                
                // Mettre à jour les statistiques si le scan est valide
                if (data.result === 'valid' && typeof data.present_count !== 'undefined') {
                    updateStats(data.present_count);
                }
            } catch (e) {
                showResult('invalid', 'Erreur réseau. Veuillez réessayer.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Scanner';
            }
        }

        btn.addEventListener('click', doScan);
        
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                doScan();
            }
        });

        // Scan automatique si token présent dans l'URL ou session
        @if(session('auto_scan_token'))
            const autoToken = @json(session('auto_scan_token'));
            if (autoToken) {
                input.value = autoToken;
                setTimeout(() => {
                    doScan();
                }, 500);
            }
        @endif

        // Vérifier aussi l'URL pour un token (si scanné depuis un autre appareil)
        const urlParams = new URLSearchParams(window.location.search);
        const urlToken = urlParams.get('token');
        if (urlToken && !input.value) {
            input.value = urlToken;
            setTimeout(() => {
                doScan();
            }, 500);
        }

        // Focus automatique sur le champ de saisie si pas de scan auto
        if (!input.value) {
            input.focus();
        }

        // Gestion du scanner QR Code
        async function startScanner() {
            const errBanner = document.getElementById('cameraErrorBanner');
            if (errBanner) {
                errBanner.classList.add('hidden');
                errBanner.textContent = '';
            }
            try {
                startScannerBtn.disabled = true;
                startScannerBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                            Activation...`;
                
                html5QrCode = new Html5Qrcode("qr-reader");
                
                await html5QrCode.start(
                    { facingMode: "environment" }, // Utiliser la caméra arrière par défaut
                    {
                        fps: 10,
                        qrbox: function(viewfinderWidth, viewfinderHeight) {
                            // Zone de scan adaptative
                            let minEdgePercentage = 0.7;
                            let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                            let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                            return {
                                width: qrboxSize,
                                height: qrboxSize
                            };
                        },
                        aspectRatio: 1.0,
                        videoConstraints: {
                            facingMode: "environment"
                        },
                        rememberLastUsedCamera: true
                    },
                    (decodedText, decodedResult) => {
                        // QR code détecté - traiter automatiquement
                        handleScannedToken(decodedText);
                    },
                    (errorMessage) => {
                        // Erreur ignorée (scan en cours)
                    }
                );

                isScanning = true;
                cameraContainer.style.display = 'block';
                scannerPlaceholder.style.display = 'none';
            } catch (err) {
                console.error(err);
                const banner = document.getElementById('cameraErrorBanner');
                if (banner) {
                    banner.textContent = 'Impossible d’accéder à la caméra. Vérifiez les permissions du navigateur ou utilisez la saisie manuelle du code.';
                    banner.classList.remove('hidden');
                }
                startScannerBtn.disabled = false;
                startScannerBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                                    </svg>
                                                                    Démarrer le scanner`;
            }
        }

        async function stopScanner() {
            if (html5QrCode && isScanning) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                    html5QrCode = null;
                    isScanning = false;
                    cameraContainer.style.display = 'none';
                    scannerPlaceholder.style.display = 'block';
                    startScannerBtn.disabled = false;
                    startScannerBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                                    </svg>
                                                    Démarrer le scanner`;
                } catch (err) {
                    console.error(err);
                }
            }
        }

        function handleScannedToken(token) {
            // Si le token est une URL, extraire le token
            let qrToken = token;
            
            // Si c'est une URL de scan, extraire le token
            const urlMatch = token.match(/\/scan\/([^\/\?]+)/);
            if (urlMatch) {
                qrToken = urlMatch[1];
            }
            
            // Si le scanner est actif, arrêter temporairement pour traiter
            if (html5QrCode && isScanning) {
                html5QrCode.pause();
            }
            
            // Scanner le token
            input.value = qrToken;
            doScan();
            // Le scanner reprendra automatiquement quand l'utilisateur ferme la pop-up
        }

        startScannerBtn.addEventListener('click', startScanner);
        stopCameraBtn.addEventListener('click', stopScanner);

        // Arrêter la caméra si on quitte la page
        window.addEventListener('beforeunload', () => {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().catch(() => {});
            }
        });
    </script>
@endsection
