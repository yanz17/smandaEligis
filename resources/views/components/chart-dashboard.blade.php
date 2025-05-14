<div>
    {{-- ChartJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex flex-col w-full text-center font-bold text-2xl">
        {{-- Jumlah Siswa per Jurusan --}}
        <h1>Jumlah Siswa per Jurusan</h1>
        <div class="h-100 flex justify-center mb-10 mt-5">
            <canvas id="siswaPerJurusanChart"></canvas>
        </div>
        
        {{-- Jumlah Eligible --}}
        <h1>Jumlah Siswa Eligible</h1>
        <div class="h-100 flex justify-center mb-10 mt-5">
            <canvas id="eligibleChart"></canvas>
        </div>
        
        {{-- Jumlah Siswa per Kelas --}}
        <h1>Jumlah Siswa per Kelas</h1>
        <div class="h-100 flex justify-center mb-10 mt-5">
            <canvas id="siswaPerKelasChart"></canvas>
        </div>

        {{-- Jumlah Eligible per Kelas --}}
        <h1>Jumlah Siswa Eligible per Kelas</h1>
        <div class="h-100 flex justify-center mb-10 mt-5">
            <canvas id="eligiblePerKelasChart"></canvas>
        </div>

        {{-- Rata-Rata Tiap Kriteria --}}
        <h1>Rata-Rata Nilai Tiap Kriteria</h1>
        <div class="h-100 flex justify-center mb-10 mt-5">
            <canvas id="rataRataKriteriaChart"></canvas>
        </div>
    </div>

    <script>
        async function fetchChartData(url) {
            const response = await fetch(url);
            return await response.json();
        }

        // Chart: Siswa per Jurusan
        fetchChartData('/charts/siswa-per-jurusan').then(data => {
            new Chart(document.getElementById('siswaPerJurusanChart'), {
                type: 'pie',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: 'Jumlah Siswa per Jurusan',
                        data: Object.values(data),
                        backgroundColor: ['#60a5fa', '#f87171']
                    }]
                }
            });
        });

        // Chart: Eligible per Jurusan
        fetchChartData('/charts/eligible-per-jurusan').then(data => {
            new Chart(document.getElementById('eligibleChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: 'Jumlah Siswa Eligible', 
                        data: Object.values(data),
                        backgroundColor: ['#34d399', '#fb923c']
                    }]
                }
            });
        });

        // Chart: Siswa per Kelas (Grouped by Jurusan)
        fetchChartData('/charts/siswa-per-kelas').then(data => {
            // Ambil semua nama kelas unik dari semua jurusan
            const allKelas = new Set();
            Object.values(data).forEach(jurusan => {
                Object.keys(jurusan).forEach(kelas => allKelas.add(kelas));
            });
            const labels = Array.from(allKelas);

            // Generate dataset per jurusan
            const colors = ['#60a5fa', '#f87171', '#34d399', '#fbbf24']; // Tambah warna jika jurusan > 4
            const datasets = Object.entries(data).map(([jurusan, kelasData], index) => {
                return {
                    label: `Jurusan ${jurusan}`,
                    data: labels.map(kelas => kelasData[kelas] ?? 0),
                    backgroundColor: colors[index % colors.length]
                };
            });

            new Chart(document.getElementById('siswaPerKelasChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Siswa per Kelas (berdasarkan Jurusan)'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        // Chart: Eligible per Kelas
        fetchChartData('/charts/eligible-per-kelas').then(data => {
            new Chart(document.getElementById('eligiblePerKelasChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: 'Jumlah Siswa Eligible per Kelas',
                        data: Object.values(data),
                        backgroundColor: '#818cf8'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Jumlah Siswa Eligible per Kelas'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        // Chart: Rata-rata Tiap Kriteria
        fetchChartData('/charts/rata-rata-kriteria').then(data => {
            new Chart(document.getElementById('rataRataKriteriaChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: 'Rata-Rata Nilai',
                        data: Object.values(data),
                        backgroundColor: '#facc15'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Rata-Rata Nilai Tiap Kriteria'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        });
    </script>
</div>
