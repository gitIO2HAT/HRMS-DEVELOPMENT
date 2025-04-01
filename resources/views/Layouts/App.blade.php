<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>HRMS - {{Request::segment(2)}} - {{Request::segment(3)}}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</head>

<body>

    <div class="container-fluid position-relative d-flex p-0 ">
        <!-- Spinner Start -->

        <!-- Spinner End -->

        <!-- sidebar-menu Start -->
        @include('Layouts.Sidebar')
        <!-- sidebar-menu Start -->



        <div class="content">

            <!-- header Start -->
            @include('Layouts.Header')
            <!-- header end -->


            <!-- Dashboard Start -->
            @yield('content')

            <!-- Dashboard end -->

        </div>

    </div>

    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('lib/chart/chart.min.js')}}"></script>
    <script src="{{asset('lib/easing/easing.min.js')}}"></script>
    <script src="{{asset('lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{asset('lib/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('lib/tempusdominus/js/moment.min.js')}}"></script>
    <script src="{{asset('lib/tempusdominus/js/moment-timezone.min.js')}}"></script>
    <script src="{{asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    @stack('javascript')
</body>
<script>
    function clearSearch() {
        document.getElementById('search').value = '';
        document.getElementById('from').value = '';
        document.getElementById('to').value = '';
        document.getElementById('leave_type').selectedIndex = 0;
        document.querySelector('form').submit();
    }
</script>
<script>
    function handleImageClick(event) {
        // Prevent the default behavior of the click event on the label
        event.preventDefault();

        // Trigger file input click when the image is clicked
        document.getElementById('profileImage').click();
    }
    function displayImage(input) {
        var reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('profilePicture').src = e.target.result;
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all checkboxes
        const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');

        checkboxes.forEach(function(checkbox) {
            // Add event listener to each checkbox
            checkbox.addEventListener('change', function() {
                // Get the parent row of the checkbox
                const row = checkbox.closest('tr');

                // Toggle the 'selected-row' class based on checkbox status
                if (checkbox.checked) {
                    row.classList.add('selected-row');
                } else {
                    row.classList.remove('selected-row');
                }
            });

            // Add click event to the entire row to toggle checkbox state
            const row = checkbox.closest('tr');
            row.addEventListener('click', function() {
                checkbox.checked = !checkbox.checked;
                // Trigger change event manually
                checkbox.dispatchEvent(new Event('change'));
            });
        });
    });
</script>

<script>
    function setMinEndTime() {
        var startDateInput = document.getElementById("scheduled_date");
        var endDateInput = document.getElementById("scheduled_end");

        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
    }
</script>
<script>
    document.getElementById('birth_date').addEventListener('change', function() {
        var birthDate = new Date(this.value);
        var today = new Date();
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    });
</script>

<script>
    function toggleEdit(id) {
        var span = document.getElementById('editable-span-' + id);
        var input = document.getElementById('editable-input-' + id);

        if (span.style.display !== 'none') {
            // Hide the span and show the input
            span.style.display = 'none';
            input.style.display = 'inline';
            input.focus();
        } else {
            // Hide the input and show the span
            span.style.display = 'inline';
            input.style.display = 'none';
            span.innerText = input.value;
        }
    }
</script>
<script>
    document.querySelector('.increment').addEventListener('click', function(event) {
     event.preventDefault(); // Prevent form submission
     var quantityInput = document.getElementById('quantity');
     var currentValue = parseFloat(quantityInput.value);
     if (currentValue < quantityInput.max) {
         quantityInput.value = (currentValue + parseFloat(quantityInput.step)).toFixed(2);
     }
 });

 document.querySelector('.decrement').addEventListener('click', function(event) {
     event.preventDefault(); // Prevent form submission
     var quantityInput = document.getElementById('quantity');
     var currentValue = parseFloat(quantityInput.value);
     if (currentValue > quantityInput.min) {
         quantityInput.value = (currentValue - parseFloat(quantityInput.step)).toFixed(2);
     }
 });
 </script>
<script>
  function updateStatus(id, status) {
    console.log("Updating status for ID:", id, "with status:", status);
    const form = document.getElementById('statusForm' + id);
    if (!form) {
        console.error("Form not found for ID:", id);
        return;
    }
    console.log("Form found. Appending hidden input for status.");

    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'status';
    hiddenInput.value = status;
    form.appendChild(hiddenInput);

    console.log("Submitting form with status:", hiddenInput.value);
    form.submit();
}
</script>
<script>
    var ctx = document.getElementById('growthChart').getContext('2d');
    var growthChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($employeeData)) !!}, // years
            datasets: [{
                label: 'Number of Employees',
                data: {!! json_encode(array_values($employeeData)) !!}, // employee counts
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Growth Rate (%)',
                data: {!! json_encode(array_values($growthRates)) !!}, // growth rates
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                type: 'line',
                yAxisID: 'growthRate'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value; // show employee count
                        }
                    }
                },
                growthRate: {
                    type: 'linear',
                    position: 'right',
                    ticks: {
                        callback: function(value) {
                            return value + '%'; // show percentage
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>

<script>
    var ctx = document.getElementById('genderChart').getContext('2d');
    var genderChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                label: 'Gender Distribution',
                data: {!! json_encode([$employeemale, $employeefemale]) !!}, // male and female counts
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
<script>
    var ctx = document.getElementById('ageChart').getContext('2d');
    var ageChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['18-22', '23-27', '28-33', '34-38', '39-43', '44-48', '49-53', '54-60'],
            datasets: [{
                label: 'Number of Employees per Age Group',
                data: {!! json_encode([$employee1822, $employee2327, $employee2833, $employee3438, $employee3943, $employee4448, $employee4953, $employee5460]) !!},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)',
                    'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)', 'rgba(201, 203, 207, 0.2)',
                    'rgba(255, 205, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)', 'rgba(201, 203, 207, 1)',
                    'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
<!--
<script>
    var allDepartments = {!! json_encode($departments) !!};
    var allCounts = {!! json_encode($counts) !!};

    var ctx = document.getElementById('departmentChart').getContext('2d');
    var departmentChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: allDepartments, // all department names
            datasets: [{
                label: 'Number of Employees per Department',
                data: allCounts, // total employee counts
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    var clickedElementIndex = elements[0].index;
                    var department = this.data.labels[clickedElementIndex];
                    var employeeCount = this.data.datasets[0].data[clickedElementIndex];

                    // Display the department and count in a styled alert
                    alert(department + " has " + employeeCount + " employees.");

                    // Highlight the clicked department by setting other data points to 0
                    var filteredData = this.data.datasets[0].data.map((count, index) => {
                        return index === clickedElementIndex ? count : 0;
                    });

                    // Update the chart with filtered data
                    this.data.datasets[0].data = filteredData;
                    this.update();
                }
            }
        }
    });
</script>
-->


<script>
    var ctx = document.getElementById('retentionRateChart').getContext('2d');

    var retentionRate = {!! json_encode($retentionRate) !!};
    var totalEmployeesAtStart = {!! json_encode($totalEmployeesAtStart) !!};
    var employeesStayed = {!! json_encode($employeesStayed) !!};

    var data = {
        labels: ['Total Employees at Start', 'Employees Stayed'],
        datasets: [{
            label: 'Retention Rate (%)',
            data: [totalEmployeesAtStart, employeesStayed],
            backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)'],
            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(54, 162, 235, 1)'],
            borderWidth: 1
        }]
    };

    var options = {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        if (context.label === 'Employees Stayed') {
                            return 'Retention Rate: ' + retentionRate.toFixed(2) + '%';
                        }
                        return context.raw;
                    }
                }
            }
        }
    };

    var retentionRateChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
</script>
<script>
    var ctx = document.getElementById('turnoverRateChart').getContext('2d');

    var turnoverRate = {!! json_encode($turnoverRate) !!};
    var employeesLeft = {!! json_encode($employeesLeft) !!};
    var averageEmployees = {!! json_encode($averageEmployees) !!};

    var data = {
        labels: ['Employees Left', 'Average Employees'],
        datasets: [{
            label: 'Turnover Rate (%)',
            data: [employeesLeft, averageEmployees],
            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
            borderWidth: 1
        }]
    };
    var options = {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        if (context.label === 'Employees Left') {
                            return 'Turnover Rate: ' + turnoverRate.toFixed(2) + '%';
                        }
                        return context.raw;
                    }
                }
            }
        }
    };
    var turnoverRateChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
</script>




</html>
