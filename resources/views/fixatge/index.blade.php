@extends('layouts.app')

@section('content')
    @php
        $esEntrada = $properTipus === 'entrada';
        $estatActual = $ultimFixatge?->check ? 'Dins de jornada' : 'Fora de jornada';
    @endphp

    <style>
        .fitxatge-page {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
            align-items: stretch;
        }

        .fitxatge-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 1.5rem;
            align-items: start;
            width: 100%;
        }

        .fitxatge-card,
        .historial-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .fitxatge-card {
            padding: 2rem;
            text-align: center;
        }

        .fitxatge-kicker {
            margin: 0 0 0.5rem;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
        }

        .fitxatge-title {
            margin: 0;
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
        }

        .fitxatge-subtitle {
            margin: 0.75rem 0 1.5rem;
            color: #64748b;
            line-height: 1.6;
            font-size: clamp(0.9rem, 1vw + 0.5rem, 1rem);
        }

        .fitxatge-gif {
            width: 100%;
            max-width: 180px;
            height: auto;
            aspect-ratio: 1/1;
            object-fit: contain;
            image-rendering: pixelated;
            transition: filter 0.2s ease;
            filter: drop-shadow(0 14px 20px rgba(245, 158, 11, 0.2));
        }

        .fitxatge-ring-button {
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1.25rem;
            transition: transform 0.18s ease, filter 0.18s ease, opacity 0.18s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .fitxatge-ring-button:hover {
            transform: translateY(-2px) scale(1.03);
        }

        .fitxatge-ring-button:hover .fitxatge-gif,
        .fitxatge-ring-button:active .fitxatge-gif {
            filter: hue-rotate(200deg) saturate(2.2) brightness(1.05) drop-shadow(0 14px 24px rgba(37, 99, 235, 0.35));
        }

        .fitxatge-ring-button:focus-visible {
            outline: 3px solid rgba(37, 99, 235, 0.45);
            outline-offset: 6px;
        }

        .fitxatge-ring-button:disabled {
            cursor: not-allowed;
            opacity: 0.55;
            transform: none;
        }

        .fitxatge-button {
            width: 100%;
            border: none;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.22);
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        .fitxatge-button--sortida {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 12px 24px rgba(239, 68, 68, 0.22);
        }

        .fitxatge-button:hover {
            transform: translateY(-1px);
        }

        .fitxatge-button:disabled {
            opacity: 0.7;
            cursor: wait;
            transform: none;
        }

        .fitxatge-status {
            margin-top: 0.9rem;
            min-height: 1.4rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
        }

        .fitxatge-status--error {
            color: #b91c1c;
        }

        .estat-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background:
                {{ $ultimFixatge?->check ? '#dcfce7' : '#e2e8f0' }}
            ;
            color:
                {{ $ultimFixatge?->check ? '#166534' : '#475569' }}
            ;
        }

        .fitxatge-meta {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e2e8f0;
            display: grid;
            gap: 0.75rem;
            text-align: left;
        }

        .fitxatge-meta-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.95rem;
        }

        .fitxatge-meta-label {
            color: #64748b;
            font-weight: 600;
        }

        .fitxatge-meta-value {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
        }

        .alert-success {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            color: #065f46;
            padding: 1rem 1.25rem;
            border-radius: 0 0.75rem 0.75rem 0;
            font-weight: 700;
        }

        .historial-card {
            padding: 1.5rem;
        }

        .historial-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .historial-filter {
            display: flex;
            align-items: end;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .historial-filter-label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .historial-filter-select {
            min-width: 220px;
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            padding: 0.75rem 0.9rem;
            font-size: 0.95rem;
            color: #0f172a;
            background: #fff;
        }

        .historial-filter-button {
            border: none;
            border-radius: 0.75rem;
            padding: 0.78rem 1rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            background: #0f172a;
            cursor: pointer;
        }

        .historial-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
        }

        .historial-count {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .historial-table {
            width: 100%;
            border-collapse: collapse;
        }

        .historial-table th,
        .historial-table td {
            padding: 0.9rem 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .historial-table th {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
        }

        .historial-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .historial-badge--entrada {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .historial-badge--sortida {
            background: #fee2e2;
            color: #b91c1c;
        }

        .historial-empty {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #64748b;
        }

        .historial-pagination {
            margin-top: 1rem;
        }

        @media (max-width: 1024px) {
            .fitxatge-grid {
                grid-template-columns: 1fr;
                margin: 0;
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .fitxatge-page {
                gap: 0.75rem;
                padding: 0;
            }

            .fitxatge-card,
            .historial-card {
                padding: 1.25rem 0.75rem;
                border-radius: 0.75rem;
                width: 100%;
            }

            .fitxatge-title {
                font-size: 1.4rem;
            }

            .fitxatge-gif {
                max-width: 140px;
            }

            .historial-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1.25rem;
            }

            .historial-filter-select {
                min-width: 0;
                width: 100%;
            }

            @media (max-width: 768px) {

                .historial-table,
                .historial-table thead,
                .historial-table tbody,
                .historial-table th,
                .historial-table td,
                .historial-table tr {
                    display: block;
                    width: 100%;
                }

                .historial-table thead {
                    display: none;
                }

                .historial-table tr {
                    margin-bottom: 1rem;
                    padding: 1rem;
                    border: 1px solid #e2e8f0;
                    border-radius: 0.75rem;
                    background: #f8fafc;
                }

                .historial-table td {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #edf2f7;
                    padding: 0.5rem 0;
                    text-align: right !important;
                }

                .historial-table td::before {
                    content: attr(data-label);
                    font-weight: 800;
                    color: #64748b;
                    text-transform: uppercase;
                    font-size: 0.7rem;
                    text-align: left;
                    margin-right: 1rem;
                }

                .historial-table td:last-child {
                    border-bottom: none;
                }

                .col-data,
                .col-dispositiu {
                    display: flex !important;
                }
            }

            @media (max-width: 480px) {
                .col-usuari {
                    min-width: 120px;
                    overflow: visible;
                }
            }

            .fitxatge-meta-row {
                flex-direction: column;
                gap: 0.25rem;
            }

            .fitxatge-meta-value {
                text-align: left;
            }
        }
    </style>

    <div class="fitxatge-page">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="fitxatge-grid">
            <section class="fitxatge-card">
                <p class="fitxatge-kicker">Control de jornada</p>
                <h1 class="fitxatge-title">Fitxa la teva {{ $esEntrada ? 'entrada' : 'sortida' }}</h1>
                <p class="fitxatge-subtitle">
                    Prem l'anell per fitxar.
                </p>

                <div class="estat-pill">{{ $estatActual }}</div>

                <form action="{{ route('fitxar.store') }}" method="POST" style="margin-top: 1.5rem;" id="fitxatge-form">
                    @csrf
                    <input type="hidden" name="ubicacio_x" id="ubicacio_x">
                    <input type="hidden" name="ubicacio_y" id="ubicacio_y">
                    <input type="hidden" name="data_local" id="data_local">
                    <button type="submit" id="fitxatge-submit" class="fitxatge-ring-button"
                        aria-label="{{ $esEntrada ? 'Fitxar entrada' : 'Fitxar sortida' }}"
                        title="{{ $esEntrada ? 'Fitxar entrada' : 'Fitxar sortida' }}">
                        <img src="{{ asset('images/fixatge-ring.png') }}" alt="" aria-hidden="true" class="fitxatge-gif">
                    </button>
                    <div id="fitxatge-status" class="fitxatge-status">
                        Caldrà autoritzar la ubicació per registrar el fitxatge.
                    </div>
                </form>

                <div class="fitxatge-meta">
                    <div class="fitxatge-meta-row">
                        <span class="fitxatge-meta-label">Pròxim registre</span>
                        <span class="fitxatge-meta-value">{{ ucfirst($properTipus) }}</span>
                    </div>
                    <div class="fitxatge-meta-row">
                        <span class="fitxatge-meta-label">Últim fitxatge</span>
                        <span class="fitxatge-meta-value">
                            {{ $ultimFixatge ? $ultimFixatge->data->format('d/m/Y H:i:s') : 'Encara no hi ha fitxatges' }}
                        </span>
                    </div>
                    <div class="fitxatge-meta-row">
                        <span class="fitxatge-meta-label">Tipus últim fitxatge</span>
                        <span class="fitxatge-meta-value">
                            {{ $ultimFixatge ? ($ultimFixatge->check ? 'Entrada' : 'Sortida') : '-' }}
                        </span>
                    </div>
                </div>
            </section>

            <section class="historial-card">
                <div class="historial-header">
                    <div>
                        <h2 class="historial-title">Historial de fitxatges</h2>
                        <div class="historial-count">
                            {{ $historial->total() }} registres
                            @if (auth()->user()->isAdmin())
                                {{ $usuariSeleccionat ? 'de l\'usuari seleccionat' : 'de tots els usuaris' }}
                            @endif
                        </div>
                    </div>

                    @if (auth()->user()->isAdmin())
                        <form method="GET" action="{{ route('fitxar.index') }}" class="historial-filter">
                            <div>
                                <label for="user_id" class="historial-filter-label">Filtrar usuari</label>
                                <select name="user_id" id="user_id" class="historial-filter-select">
                                    <option value="">Tots els usuaris</option>
                                    @foreach ($usuaris as $usuari)
                                        <option value="{{ $usuari->id }}" {{ (string) $usuariSeleccionat === (string) $usuari->id ? 'selected' : '' }}>
                                            {{ $usuari->nom }} {{ $usuari->cognom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="historial-filter-button">Aplicar</button>
                        </form>
                    @endif
                </div>

                @if ($historial->count())
                    <table class="historial-table">
                        <thead>
                            <tr>
                                @if (auth()->user()->isAdmin())
                                    <th class="col-usuari">Usuari</th>
                                @endif
                                <th>Tipus</th>
                                <th class="col-data">Data</th>
                                <th>Hora</th>
                                @if (auth()->user()->isAdmin())
                                    <th class="col-dispositiu">Dispositiu</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historial as $registre)
                                <tr>
                                    @if (auth()->user()->isAdmin())
                                        <td class="col-usuari" data-label="Usuari">
                                            <strong>{{ $registre->user?->nom }} {{ $registre->user?->cognom }}</strong>
                                        </td>
                                    @endif
                                    <td data-label="Tipus">
                                        <span
                                            class="historial-badge {{ $registre->check ? 'historial-badge--entrada' : 'historial-badge--sortida' }}">
                                            {{ $registre->check ? 'Entrada' : 'Sortida' }}
                                        </span>
                                    </td>
                                    <td class="col-data" data-label="Data">{{ $registre->data->format('d/m/Y') }}</td>
                                    <td data-label="Hora">{{ $registre->data->format('H:i:s') }}</td>
                                    @if (auth()->user()->isAdmin())
                                        <td class="col-dispositiu" data-label="Dispositiu">
                                            {{ $registre->dispositiu ?: 'No disponible' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="historial-pagination">
                        {{ $historial->links() }}
                    </div>
                @else
                    <div class="historial-empty">
                        Encara no hi ha cap fitxatge registrat.
                    </div>
                @endif
            </section>
        </div>
    </div>

    <script>
        (() => {
            const form = document.getElementById('fitxatge-form');
            const submitButton = document.getElementById('fitxatge-submit');
            const statusBox = document.getElementById('fitxatge-status');
            const inputX = document.getElementById('ubicacio_x');
            const inputY = document.getElementById('ubicacio_y');

            if (!form || !submitButton || !statusBox || !inputX || !inputY) {
                return;
            }

            let sendingWithLocation = false;

            const setStatus = (message, isError = false) => {
                statusBox.textContent = message;
                statusBox.classList.toggle('fitxatge-status--error', isError);
            };

            const lockButton = (message) => {
                submitButton.disabled = true;
                setStatus(message, true);
            };

            const unlockButton = (message) => {
                submitButton.disabled = false;
                setStatus(message, false);
            };

            const fillLocation = (position) => {
                // Guardem coordenades en micrograus perquè les columnes actuals són enters.
                inputX.value = Math.round(position.coords.latitude * 1000000);
                inputY.value = Math.round(position.coords.longitude * 1000000);
            };

            const requestLocation = () => new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(
                    (position) => resolve(position),
                    (error) => reject(error),
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0,
                    }
                );
            });

            // Geolocalització obligatòria: deshabilitem fins que estigui concedida.
            submitButton.disabled = true;

            form.addEventListener('submit', (event) => {
                if (sendingWithLocation) {
                    return;
                }

                event.preventDefault();

                if (!navigator.geolocation) {
                    lockButton('Aquest dispositiu o navegador no permet obtenir la ubicació.');
                    return;
                }

                submitButton.disabled = true;
                setStatus('Verificant ubicació...');

                requestLocation()
                    .then((position) => {
                        fillLocation(position);
                        sendingWithLocation = true;

                        const now = new Date();
                        const tzOffset = now.getTimezoneOffset() * 60000;
                        const localISOTime = (new Date(now - tzOffset)).toISOString().slice(0, 19).replace('T', ' ');
                        document.getElementById('data_local').value = localISOTime;

                        setStatus('Ubicació obtinguda. Registrant el fitxatge...');
                        form.submit();
                    })
                    .catch((error) => {
                        const errorMessages = {
                            1: 'Has de permetre l\'accés a la ubicació per poder fitxar.',
                            2: 'No s\'ha pogut determinar la ubicació actual.',
                            3: 'La geolocalització ha trigat massa temps. Torna-ho a provar.',
                        };

                        lockButton(errorMessages[error.code] || 'No s\'ha pogut obtenir la ubicació.');
                    });
            });

            // Demanem permisos tan bon punt entrem a la pàgina
            if (!navigator.geolocation) {
                lockButton('Aquest dispositiu o navegador no permet obtenir la ubicació.');
                return;
            }

            setStatus('Cal autoritzar la ubicació per poder fitxar.');

            const handleInitialLocation = () => {
                setStatus('Sol·licitant ubicació...');
                requestLocation()
                    .then((position) => {
                        fillLocation(position);
                        unlockButton('Ubicació autoritzada. Ja pots fitxar.');
                    })
                    .catch((error) => {
                        const errorMessages = {
                            1: 'Has de permetre l\'accés a la ubicació per poder fitxar.',
                            2: 'No s\'ha pogut determinar la ubicació actual.',
                            3: 'La geolocalització ha trigat massa temps. Recarrega la pàgina i torna-ho a provar.',
                        };
                        lockButton(errorMessages[error.code] || 'No s\'ha pogut obtenir la ubicació.');
                    });
            };

            // Si el navegador suporta Permissions API, reaccionem a canvis.
            if (navigator.permissions?.query) {
                navigator.permissions.query({ name: 'geolocation' }).then((result) => {
                    if (result.state === 'granted') {
                        handleInitialLocation();
                        return;
                    }

                    if (result.state === 'denied') {
                        lockButton('Ubicació denegada. Habilita-la al navegador per poder fitxar.');
                        return;
                    }

                    // prompt
                    handleInitialLocation();

                    result.addEventListener('change', () => {
                        if (result.state === 'granted') {
                            handleInitialLocation();
                        } else if (result.state === 'denied') {
                            lockButton('Ubicació denegada. Habilita-la al navegador per poder fitxar.');
                        }
                    });
                }).catch(() => {
                    handleInitialLocation();
                });
            } else {
                handleInitialLocation();
            }
        })();
    </script>
@endsection