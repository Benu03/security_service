@php
$doc = [
    (object)[ 'id' => 1, 'doc_ref' => 'DOC-001', 'progress' => 25 ],
    (object)[ 'id' => 2, 'doc_ref' => 'DOC-002', 'progress' => 45 ],
    (object)[ 'id' => 3, 'doc_ref' => 'DOC-003', 'progress' => 75 ],
];
@endphp

<div class="row">
    <div class="col-12">
        <div class="card" style="margin-top: 35px; margin-left: 5px">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="card-title"><b>{{ $title }}</b></h2>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row justify-content-center align-items-center" style="min-height: 400px;">

                    <div class="col-md-6">
                        <div class="data-container p-4 border rounded bg-light shadow-sm" style="min-height: 400px;">
                            <h5 class="text-center mb-3">Top 8 Ongoing Project</h5>
                            <ul class="list-group shadow-sm rounded">
                                @foreach ($doc as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3 border-0 shadow-sm mb-2 rounded">
                                        <div class="d-flex align-items-center">
                                            <a href=""
                                               class="btn btn-md rounded-pill text-primary bg-transparent px-3 me-2">
                                                <i class="fas fa-file-alt me-1"></i> {{ $item->doc_ref }}
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm rounded-pill border-primary text-white bg-primary"
                                                    data-toggle="modal"
                                                    data-target="#remarkModal{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <span class="badge
                                            @if($item->progress <= 30) text-danger
                                            @elseif($item->progress > 30 && $item->progress <= 60) text-warning
                                            @else text-success
                                            @endif fs-4 fw-bold px-3 py-2 rounded-pill"
                                              style="background-color: #f4f4f4; font-size: 1.15rem; font-weight: bold;">
                                            {{ $item->progress }}%
                                        </span>
                                    </li>

                                    @includeIf('dashboard.modal.detail_doc_ref', ['item' => $item])
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="chart-container d-flex justify-content-center align-items-center p-4 border rounded bg-light shadow-sm"
                             style="width: 100%; max-width: 550px; min-height: 550px;">
                             <canvas id="statusPieChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('statusPieChart').getContext('2d');

    const status = ['Done', 'Ongoing', 'Pending'];
    const count = [5, 3, 2];
    const percentage = [50, 30, 20];
    const colors = ['#28a745', '#ffc107', '#dc3545'];

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: status.map((s, i) => `${s} (${count[i]})`),
            datasets: [{
                data: percentage,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: "#fff",
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Project Status',
                    font: {
                        size: 20,
                        weight: 'bold'
                    },
                    color: '#333',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 14
                        },
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            let index = tooltipItem.dataIndex;
                            return `${status[index]}: ${count[index]} (${percentage[index]}%)`;
                        }
                    },
                    backgroundColor: "rgba(0,0,0,0.7)",
                    titleFont: { size: 14 },
                    bodyFont: { size: 14 },
                    padding: 10
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 16
                    },
                    formatter: function (value, context) {
                        return `${value}%`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>