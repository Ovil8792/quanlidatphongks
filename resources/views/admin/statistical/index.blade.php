@extends("admin.layout.main")
@section("main")
<div class="container py-3">
    <h2 class="mb-3">Thống kê doanh thu</h2>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Hôm nay</div>
                    <div class="fs-4 fw-bold">{{ number_format($revenueDay, 0, ',', '.') }} ₫</div>
                    <div class="small mt-1">
                        @php $up = $growthDay >= 0; @endphp
                        <span class="{{ $up ? 'text-success' : 'text-danger' }}">
                            {{ $up ? '▲' : '▼' }} {{ number_format(abs($growthDay), 2, ',', '.') }}%
                        </span>
                        so với hôm qua
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Tuần này</div>
                    <div class="fs-4 fw-bold">{{ number_format($revenueWeek, 0, ',', '.') }} ₫</div>
                    <div class="small mt-1">
                        @php $up = $growthWeek >= 0; @endphp
                        <span class="{{ $up ? 'text-success' : 'text-danger' }}">
                            {{ $up ? '▲' : '▼' }} {{ number_format(abs($growthWeek), 2, ',', '.') }}%
                        </span>
                        so với tuần trước
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Tháng này</div>
                    <div class="fs-4 fw-bold">{{ number_format($revenueMonth, 0, ',', '.') }} ₫</div>
                    <div class="small mt-1">
                        @php $up = $growthMonth >= 0; @endphp
                        <span class="{{ $up ? 'text-success' : 'text-danger' }}">
                            {{ $up ? '▲' : '▼' }} {{ number_format(abs($growthMonth), 2, ',', '.') }}%
                        </span>
                        so với tháng trước
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Năm nay</div>
                    <div class="fs-4 fw-bold">{{ number_format($revenueYear, 0, ',', '.') }} ₫</div>
                    <div class="small mt-1">
                        @php $up = $growthYear >= 0; @endphp
                        <span class="{{ $up ? 'text-success' : 'text-danger' }}">
                            {{ $up ? '▲' : '▼' }} {{ number_format(abs($growthYear), 2, ',', '.') }}%
                        </span>
                        so với năm trước
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-semibold">Xu hướng 14 ngày gần nhất</div>
                    <small class="text-muted">Doanh thu theo ngày</small>
                </div>
                <canvas id="sparkline14" height="80"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const ctx = document.getElementById('sparkline14');
        if (!ctx) return;
        const labels = @json($labels14);
        const values = @json($values14);
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data: values,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.08)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 0,
                    pointHitRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { display: true, grid: { display: false } },
                    y: { display: true, grid: { color: 'rgba(0,0,0,0.05)' }, beginAtZero: true }
                },
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                elements: { line: { borderJoinStyle: 'round' } }
            }
        });
    })();
</script>
@endpush