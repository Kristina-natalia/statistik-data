<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #444;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #007BFF;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #DC3545;
        }

        .btn-danger:hover {
            background-color: #b82534;
        }

        .card {
            background-color: #555;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Statistik Data</h1>
        <form method="POST" class="form-inline justify-content-center mb-4">
            <div class="form-group mx-sm-3">
                <input type="number" name="data" class="form-control" placeholder="Data" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Data</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>No Data</th>
                    <th>Data</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = isset($_POST['data']) ? $_POST['data'] : "";
                $dataList = isset($_POST['dataList']) ? $_POST['dataList'] : [];
                if ($data !== "") {
                    $dataList[] = $data;
                }
                if (isset($_GET['action'])) {
                    if ($_GET['action'] === 'edit') {
                        $index = $_GET['index'];
                        if (isset($dataList[$index])) {
                            $editValue = $dataList[$index];
                            $editIndex = $index;
                        }
                    } elseif ($_GET['action'] === 'delete') {
                        $index = $_GET['index'];
                        if (isset($dataList[$index])) {
                            unset($dataList[$index]);
                            $dataList = array_values($dataList); // Reindex array
                        }
                    } elseif ($_GET['action'] === 'reset') {
                        $dataList = [];
                    }
                }
                foreach ($dataList as $key => $value) { ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $value ?></td>
                        <td>
                            <a href="index.php?action=edit&index=<?= $key }" class="btn btn-primary btn-sm">Edit</a>
                            <a href="index.php?action=delete&index=<?= $key }" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Statistik:</h2>
                <p class="card-text">Mean: <span id="mean"><?= calculateMean($dataList) ?></span></p>
                <p class="card-text">Modus: <span id="modus"><?= calculateModus($dataList) ?></span></p>
                <p class="card-text">Median: <span id="median"><?= calculateMedian($dataList) ?></span></p>
            </div>
        </div>
        <canvas id="myChart" width="400" height="200"></canvas>
        <canvas id="histogram" width="400" height="200"></canvas>
        <a href="index.php?action=reset" class="btn btn-danger mt-3">Reset Data</a>
    </div>

    <script>
        function calculateMean(dataList) {
            if (dataList.length > 0) {
                let sum = dataList.reduce((acc, val) => acc + val, 0);
                return (sum / dataList.length).toFixed(2);
            } else {
                return 'Tidak ada data';
            }
        }

        function calculateModus(dataList) {
            if (dataList.length > 0) {
                let counts = {};
                let maxCount = 0;
                let modus = [];

                for (let data of dataList) {
                    counts[data] = (counts[data] || 0) + 1;
                    if (counts[data] > maxCount) {
                        maxCount = counts[data];
                        modus = [data];
                    } else if (counts[data] === maxCount && !modus.includes(data)) {
                        modus.push(data);
                    }
                }

                return modus.join(', ');
            } else {
                return 'Tidak ada data';
            }
        }

        function calculateMedian(dataList) {
            if (dataList.length > 0) {
                let sortedData = dataList.slice().sort((a, b) => a - b);
                let middle = Math.floor(sortedData.length / 2);

                if (sortedData.length % 2 === 0) {
                    let median = (sortedData[middle - 1] + sortedData[middle]) / 2;
                    return median.toFixed(2);
                } else {
                    return sortedData[middle].toFixed(2);
                }
            } else {
                return 'Tidak ada data';
            }
        }

        document.getElementById('mean').textContent = calculateMean(<?= json_encode($dataList) ?>);
        document.getElementById('modus').textContent = calculateModus(<?= json_encode($dataList) ?>);
        document.getElementById('median').textContent = calculateMedian(<?= json_encode($dataList) ?>);

        var ctx = document.getElementById('myChart').getContext('2d');
        var dataList = <?= json_encode($dataList) ?>;
        var dataLabels = Array.from({ length: dataList.length }, (_, i) => i + 1);

        var myChart = new Chart(ctx, {
            type: 'scatter',
            data: {
                labels: dataLabels,
                datasets: [{
                    label: 'Data',
                    data: dataList,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHitRadius: 10,
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom'
                    },
                    y: {
                        min: Math.min(...dataList) - 1,
                        max: Math.max(...dataList) + 1
                    }
                }
            }
        });

        var histogramCtx = document.getElementById('histogram').getContext('2d');
        var histogram = new Chart(histogramCtx, {
            type: 'bar',
            data: {
                labels: dataLabels,
                datasets: [{
                    label: 'Data',
                    data: dataList,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom'
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
