let chartHasilSet = false
let chartHasil

$('#periode-penilaian').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'yy-mm',
    onClose: function (dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    }
})

$('#search-hasil').on('click', function () {
    if ($('#periode-penilaian').val().length == 0) {
        alert('Masukkan periode penilaian')
    } else {
        getFinalResult($('#periode-penilaian').val())
    }
})

function getFinalResult(params) {
    $('#hasil-loader').html(`<div class="loader4"></div>
                            <h5 style="margin-top: 2.5rem">Membuat peringkat</h5>`)
    $('#hasil-loader').show()
    $('#table-hasil-penilaian').hide()
    $('#hasil-chart').hide()
    $('#btn-print-hasil').hide()
    $.ajax({
        type: 'post',
        url: '/penilaian-karyawan/final-result/get',
        data: {
            "periode": params,
        },
        success: function (result) {
            $('#btn-print-hasil').show()
            $('#tbody-hasil').empty()
            let no = 1;
            $.each(result, function (i, v) {
                $('#tbody-hasil').append(`<tr>
                                                <td>${no}</td>
                                                <td>${v.nama}</td>
                                                <td>${v.nilai}</td>
                                            </tr>`)
                no = no + 1;
            })

            $('#hasil-loader').empty()
            $('#hasil-loader').hide()
            $('#table-hasil-penilaian').show()
            $('#hasil-chart').show()

            if (chartHasilSet == false) {
                createChartHasil(result)
            } else {
                updateChartHasil(chartHasil, result)
            }
        }
    })
}

function createChartHasil(result) {
    let chartLabels = []
    let chartData = []
    let chartHeight = 0

    $.each(result, function (i, v) {
        chartLabels.push(v.nama)
        chartData.push(v.nilai)
        chartHeight = chartHeight + 75
    })

    $('#hasil-chart').css('max-height', `${chartHeight}px`)

    let ctx = document.getElementById("hasil-chart").getContext('2d')

    chartHasil = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Nilai',
                data: chartData,
                borderWidth: 3,
                borderColor: mainColor,
                backgroundColor: chartBarColor,
                barThickness: 25
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    chartHasilSet = true
}

function updateChartHasil(chartHasil, result) {
    let chartLabels = []
    let chartData = []
    let chartHeight = 0

    $.each(result, function (i, v) {
        chartLabels.push(v.nama)
        chartData.push(v.nilai)
        chartHeight = chartHeight + 75
    })

    $('#hasil-chart').css('max-height', `${chartHeight}px`)

    chartHasil.data = {
        labels: chartLabels,
        datasets: [{
            label: 'Nilai',
            data: chartData,
            borderWidth: 3,
            borderColor: mainColor,
            backgroundColor: chartBarColor,
            barThickness: 25
        }]
    }

    chartHasil.update()
}

$('#btn-print-hasil').on('click', function () {
    $('body').addClass('layout-fullwidth')
    window.print()
    setTimeout(() => {
        $('body').removeClass('layout-fullwidth')
    }, 1);
})
