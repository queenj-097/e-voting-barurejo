<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Live Count E-Voting Barurejo</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: #eef4f1;
            color: #203029;
            font-family: Arial, sans-serif;
        }

        .live-header {
            background: linear-gradient(135deg, #0f5137, #198754);
            color: white;
            padding: 20px 28px;
        }

        .live-logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
            background: white;
            border-radius: 16px;
            padding: 6px;
        }

        .status-live {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-weight: 700;
        }

        .live-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #6cff9e;
            box-shadow: 0 0 12px rgba(108, 255, 158, 0.95);
            animation: pulse 1.2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.35);
                opacity: 0.55;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .summary-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .summary-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 34px;
            font-weight: 800;
        }

        .dusun-section {
            background: white;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
        }

        .dusun-title {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .dusun-total {
            color: #6c757d;
            margin-bottom: 18px;
        }

        .candidate-row {
            display: grid;
            grid-template-columns: 74px 90px 1fr 90px;
            gap: 16px;
            align-items: center;
            padding: 14px 0;
            border-top: 1px solid #e9ecef;
        }

        .candidate-row:first-of-type {
            border-top: 0;
        }

        .candidate-number {
            width: 62px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: #198754;
            color: white;
            font-size: 28px;
            font-weight: 800;
        }

        .candidate-photo {
            width: 74px;
            height: 74px;
            object-fit: cover;
            border-radius: 16px;
            border: 4px solid #e7eee9;
        }

        .candidate-photo-placeholder {
            width: 74px;
            height: 74px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: #e9ecef;
            color: #6c757d;
            font-size: 28px;
        }

        .candidate-name {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .progress {
            height: 18px;
            border-radius: 999px;
            background: #e9ecef;
        }

        .progress-bar {
            background: linear-gradient(90deg, #0f5137, #198754);
            font-size: 11px;
            font-weight: 700;
        }

        .candidate-score {
            text-align: right;
        }

        .candidate-votes {
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
        }

        .candidate-percentage {
            color: #6c757d;
            margin-top: 4px;
        }

        .latest-vote-toast {
            position: fixed;
            top: 22px;
            right: 22px;
            z-index: 9999;
            width: 360px;
            max-width: calc(100vw - 44px);
            border-radius: 22px;
            background: white;
            color: #203029;
            padding: 20px;
            box-shadow: 0 18px 60px rgba(0, 0, 0, 0.22);
            transform: translateY(-140%);
            opacity: 0;
            transition: 0.3s ease;
        }

        .latest-vote-toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .latest-vote-title {
            color: #198754;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .latest-vote-name {
            font-size: 22px;
            font-weight: 800;
        }

        .latest-vote-meta {
            color: #6c757d;
            margin-top: 4px;
        }

        .updated-at {
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .candidate-row {
                grid-template-columns: 62px 72px 1fr;
            }

            .candidate-score {
                grid-column: 1 / -1;
                display: flex;
                justify-content: space-between;
                text-align: left;
                padding-left: 154px;
            }

            .candidate-name {
                font-size: 18px;
            }

            .summary-value {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<header class="live-header">
    <div class="container-fluid">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

            <div class="d-flex align-items-center gap-3">

                <img
                    src="{{ asset('images/logos/logo-bwi.png') }}"
                    alt="Logo Kabupaten Banyuwangi"
                    class="live-logo"
                >

                <div>
                    <div class="small text-white-50">
                        REKAPITULASI LANGSUNG ·
                        <span id="sessionTitle">
                            SEMUA DUSUN
                        </span>
                    </div>

                    <h1 class="fw-bold mb-0">
                        {{ $setting?->title ?? 'Live Count E-Voting Barurejo' }}
                    </h1>

                    <div class="updated-at">
                        Diperbarui:
                        <span id="updatedAt">
                            -
                        </span>
                    </div>
                </div>

            </div>

            <div class="status-live">
                <span class="live-dot"></span>
                LIVE COUNT
            </div>

        </div>

    </div>
</header>

<main class="container-fluid py-4 px-4">

    <div class="row g-4 mb-4">

        <div class="col-6 col-xl-3">
            <div class="card summary-card h-100">
                <div class="card-body p-4">

                    <div class="summary-label">
                        Total DPT Sesi
                    </div>

                    <div
                        class="summary-value"
                        id="totalVoters"
                    >
                        0
                    </div>

                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card summary-card h-100">
                <div class="card-body p-4">

                    <div class="summary-label">
                        Sudah Memilih
                    </div>

                    <div
                        class="summary-value text-primary"
                        id="votedVoters"
                    >
                        0
                    </div>

                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card summary-card h-100">
                <div class="card-body p-4">

                    <div class="summary-label">
                        Suara Sah
                    </div>

                    <div
                        class="summary-value text-success"
                        id="countedBallots"
                    >
                        0
                    </div>

                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card summary-card h-100">
                <div class="card-body p-4">

                    <div class="summary-label">
                        Partisipasi Sesi
                    </div>

                    <div
                        class="summary-value"
                        id="participationPercentage"
                    >
                        0%
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div id="groupsContainer">

        <div class="text-center py-5 text-secondary">

            <div class="spinner-border text-success mb-3"></div>

            <div>
                Memuat hasil live count...
            </div>

        </div>

    </div>

</main>

<div
    class="latest-vote-toast"
    id="latestVoteToast"
>
    <div class="latest-vote-title">
        <i class="bi bi-check-circle-fill me-2"></i>
        Suara Baru Masuk
    </div>

    <div
        class="latest-vote-name"
        id="latestVoteName"
    >
        -
    </div>

    <div
        class="latest-vote-meta"
        id="latestVoteMeta"
    >
        -
    </div>
</div>

<script>
    const selectedSession = new URLSearchParams(
        window.location.search
    ).get('session');

    const sessionTitles = {
        '1': 'SESI 1 — SENEPOSARI',
        '2': 'SESI 2 — SENEPOLOR & KRAJAN',
        '3': 'SESI 3 — SUMBERURIP & SUMBERMANGGIS',
    };

    const sessionTitleElement =
        document.getElementById('sessionTitle');

    if (sessionTitleElement) {
        sessionTitleElement.textContent =
            selectedSession
            ? (sessionTitles[selectedSession] ?? 'SEMUA DUSUN')
            : 'SEMUA DUSUN';
    }

    const baseDataUrl =
        @json(route('results.live-count.data'));

    const dataUrl = selectedSession
        ? baseDataUrl + '?session=' + encodeURIComponent(selectedSession)
        : baseDataUrl;

    const groupsContainer =
        document.getElementById('groupsContainer');

    const latestVoteToast =
        document.getElementById('latestVoteToast');

    const latestVoteName =
        document.getElementById('latestVoteName');

    const latestVoteMeta =
        document.getElementById('latestVoteMeta');

    let lastBallotId = null;
    let toastTimer = null;
    let hasLoadedOnce = false;

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function normalizePhotoUrl(photoUrl) {
        if (!photoUrl) {
            return null;
        }

        try {
            const url = new URL(photoUrl);

            return window.location.origin + url.pathname;
        } catch (error) {
            return photoUrl;
        }
    }

    function formatPercentage(value) {
        const number = Number(value ?? 0);

        return number.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        }) + '%';
    }

    function updateSummary(summary) {
        document.getElementById(
            'totalVoters'
        ).textContent =
            summary.total_voters ?? 0;

        document.getElementById(
            'votedVoters'
        ).textContent =
            summary.voted_voters ?? 0;

        document.getElementById(
            'countedBallots'
        ).textContent =
            summary.counted_ballots ?? 0;

        document.getElementById(
            'participationPercentage'
        ).textContent =
            formatPercentage(
                summary.participation_percentage
            );
    }

    function renderGroups(groups) {
        if (
            !Array.isArray(groups)
            || groups.length === 0
        ) {
            groupsContainer.innerHTML = `
                <div class="alert alert-warning text-center">
                    Belum ada data kandidat untuk sesi ini.
                </div>
            `;

            return;
        }

        /*
        * Jika tidak memilih sesi tertentu,
        * gabungkan seluruh dusun menjadi satu rekap.
        */
        let displayGroups = groups;

        if (!selectedSession) {
            const candidateMap = {};
            let totalVotes = 0;

            groups.forEach(function (group) {
                totalVotes += Number(group.total_votes ?? 0);

                const candidates =
                    Array.isArray(group.candidates)
                        ? group.candidates
                        : [];

                candidates.forEach(function (candidate) {

                    /*
                    * Nomor kandidat digunakan sebagai kunci
                    * karena nomor kandidat sama di setiap dusun.
                    */
                    const key = String(
                        candidate.number ?? ''
                    );

                    if (!candidateMap[key]) {
                        candidateMap[key] = {
                            number: candidate.number,
                            name: candidate.name,
                            photo_url: candidate.photo_url,
                            votes: 0,
                        };
                    }

                    candidateMap[key].votes += Number(
                        candidate.votes ?? 0
                    );
                });
            });

            const aggregatedCandidates =
                Object.values(candidateMap);

            aggregatedCandidates.forEach(function (candidate) {
                candidate.percentage =
                    totalVotes > 0
                        ? (candidate.votes / totalVotes) * 100
                        : 0;
            });

            /*
            * Buat satu group saja untuk semua dusun.
            */
            displayGroups = [
                {
                    dusun: 'SEMUA DUSUN',
                    total_votes: totalVotes,
                    candidates: aggregatedCandidates,
                }
            ];
        }

        groupsContainer.innerHTML = displayGroups
            .map(function (group) {

                const candidates =
                    Array.isArray(group.candidates)
                        ? group.candidates
                        : [];

                const candidateRows = candidates
                    .map(function (candidate) {

                        const photoUrl =
                            normalizePhotoUrl(
                                candidate.photo_url
                            );

                        const photoHtml = photoUrl
                            ? `
                                <img
                                    src="${escapeHtml(photoUrl)}"
                                    alt="Foto ${escapeHtml(candidate.name)}"
                                    class="candidate-photo"
                                >
                            `
                            : `
                                <div class="candidate-photo-placeholder">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            `;

                        const percentage = Number(
                            candidate.percentage ?? 0
                        );

                        const safePercentage = Math.min(
                            Math.max(percentage, 0),
                            100
                        );

                        return `
                            <div class="candidate-row">

                                <div class="candidate-number">
                                    ${escapeHtml(candidate.number)}
                                </div>

                                ${photoHtml}

                                <div>
                                    <div class="candidate-name">
                                        ${escapeHtml(candidate.name)}
                                    </div>

                                    <div class="progress">

                                        <div
                                            class="progress-bar"
                                            role="progressbar"
                                            style="width: ${safePercentage}%"
                                            aria-valuenow="${safePercentage}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        >
                                        </div>

                                    </div>
                                </div>

                                <div class="candidate-score">

                                    <div class="candidate-votes">
                                        ${escapeHtml(candidate.votes)}
                                    </div>

                                    <div class="candidate-percentage">
                                        ${formatPercentage(percentage)}
                                    </div>

                                </div>

                            </div>
                        `;
                    })
                    .join('');

                /*
                * Untuk SEMUA DUSUN jangan tulis
                * "Dusun SEMUA DUSUN".
                */
                const title =
                    group.dusun === 'SEMUA DUSUN'
                        ? 'Rekapitulasi Semua Dusun'
                        : `Dusun ${escapeHtml(group.dusun)}`;

                return `
                    <section class="dusun-section">

                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2"
                        >
                            <div>

                                <div class="dusun-title">
                                    ${title}
                                </div>

                                <div class="dusun-total">
                                    Total suara sah:
                                    <strong>
                                        ${escapeHtml(group.total_votes)}
                                    </strong>
                                </div>

                            </div>
                        </div>

                        ${candidateRows}

                    </section>
                `;
            })
            .join('');
    }

    function showLatestVote(latestVote) {
        if (
            !latestVote
            || !latestVote.ballot_id
        ) {
            return;
        }

        if (lastBallotId === null) {
            lastBallotId =
                latestVote.ballot_id;

            return;
        }

        if (
            Number(latestVote.ballot_id)
            === Number(lastBallotId)
        ) {
            return;
        }

        lastBallotId =
            latestVote.ballot_id;

        const dusuns =
            Array.isArray(latestVote.dusuns)
                ? latestVote.dusuns.join(', ')
                : '-';

        latestVoteName.textContent =
            `Nomor ${latestVote.candidate_number} — `
            + latestVote.candidate_name;

        latestVoteMeta.textContent =
            `Dusun ${dusuns} · `
            + latestVote.counted_at;

        latestVoteToast.classList.add(
            'show'
        );

        clearTimeout(toastTimer);

        toastTimer = setTimeout(function () {
            latestVoteToast.classList.remove(
                'show'
            );
        }, 4000);
    }

    async function fetchLiveCount() {
        try {
            const response = await fetch(
                dataUrl,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                    cache: 'no-store',
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Gagal mengambil data live count.'
                );
            }

            const data =
                await response.json();

            updateSummary(
                data.summary ?? {}
            );

            renderGroups(
                data.groups ?? []
            );

            document.getElementById(
                'updatedAt'
            ).textContent =
                data.updated_at ?? '-';

            if (hasLoadedOnce) {
                showLatestVote(
                    data.latest_vote
                );
            } else if (
                data.latest_vote?.ballot_id
            ) {
                lastBallotId =
                    data.latest_vote.ballot_id;
            }

            hasLoadedOnce = true;
        } catch (error) {
            groupsContainer.innerHTML = `
                <div class="alert alert-danger text-center">
                    Data live count tidak dapat dimuat.
                    Periksa koneksi ke server.
                </div>
            `;
        }
    }

    fetchLiveCount();

    setInterval(
        fetchLiveCount,
        2000
    );
</script>

</body>
</html>