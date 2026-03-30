@extends('backend.properties.template')

@section('property-content')

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Performance Trend Analysis - Ref: {{ $property->ref }}</h3>
                <div class="card-tools">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success active" data-period="weekly">Weekly</button>
                        <button type="button" class="btn btn-outline-success" data-period="monthly">Monthly</button>
                        <button type="button" class="btn btn-outline-success" data-period="yearly">Yearly</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; width: 100%; height: 500px;">
                    <canvas id="propertyPerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<br>
<br>
<br>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detailed Performance Analytics</h3>
            </div>
            <div class="card-body">
                <div class="row" id="analyticsKPIs">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<div>
<h3 class="card-title">Property Stats</h3>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th colspan="3">Past 30 days</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="50%">Views</td>
                        <td width="50%" class="text-center">{{ $property_30days_view_total }}</td>
                    </tr>
                    <tr>
                        <td>Enquiries</td>
                        <td class="text-center">{{ $total_past_30days }}</td>
                    </tr>
                    <tr>
                        <td>Whatsapp Clicks</td>
                        <td class="text-center">
                        {{ $whatsappClickCount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="ref-link">
                            Search Views
                            @if(count($property_search_30days_stats))
                            - <a href="#" data-toggle="modal" data-target="#search-url-30days">See URLs</a>
                            @endif
                        </td>
                        <td class="text-center">{{ $property_30days_search_total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="3">All-time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="50%">Views</td>
                        <td width="50%" class="text-center">{{ $property_view_total }}</td>
                    </tr>
                    <tr>
                        <td>Enquiries</td>
                        <td class="text-center">
                            {{ count($property->propertyEnquiries) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Whatsapp Clicks</td>
                        <td class="text-center">
                        {{ $whatsappClickCount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="ref-link">
                            Search Views
                            @if(count($property_search_stats))
                            - <a href="#" data-toggle="modal" data-target="#search-url">See URLs</a>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $property_search_total  }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="search-url" class="modal fade " role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Search URLs - All-time</h4>
            </div>
            <div class="modal-body">
                <div class="">
                    @if(count($property_search_stats))
                        @foreach( $property_search_stats as $stat )
                            <div>({{ $stat->total }}) <a href="{{ url($stat->data) }}" target="_blank">{{ url($stat->data) }}</a> </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="search-url-30days" class="modal fade " role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Search URLs - Past 30 days</h4>
            </div>
            <div class="modal-body">
                <div class="">
                    @if(count($property_search_30days_stats))
                        @foreach( $property_search_30days_stats as $stat )
                            <div>({{ $stat->total }}) <a href="{{ url($stat->data) }}" target="_blank">{{ url($stat->data) }}</a> </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<div class="form-group sticky-buttons">
    @include('backend.properties.sticky-buttons')


    @if(isset($property) && $property->id)
    <a href="{{ route('properties.generate-stats-pdf', $property->id) }}" class="btn btn-primary">
        Download Stats PDF
    </a>
@endif


</div>


@endsection

@push('footerscripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Safely parse the display date
    const rawDisplayDate = "{{ $property->display_date }}";
    
    // Function to validate and parse date
    function safeParseDate(dateString) {
        // Check if date is empty or invalid
        if (!dateString || dateString.trim() === '') {
            console.warn('Invalid or empty display date. Using current date.');
            return moment(); // Fallback to current date
        }

        // Try parsing the date
        const parsedDate = moment(dateString);
        
        // Check if the parsed date is valid
        if (!parsedDate.isValid()) {
            console.warn(`Unable to parse date: ${dateString}. Using current date.`);
            return moment(); // Fallback to current date
        }

        return parsedDate;
    }

    // Configuration object for chart and data generation
    const CONFIG = {
        colors: {
            views: 'rgba(75, 192, 192, 1)',     // Teal
            searches: 'rgba(255, 99, 132, 1)',  // Pink
            enquiries: 'rgba(54, 162, 235, 1)', // Blue
            whatsapp: 'rgba(255, 206, 86, 1)'   // Yellow
        },
        colorsTransparent: {
            views: 'rgba(75, 192, 192, 0.2)',
            searches: 'rgba(255, 99, 132, 0.2)',
            enquiries: 'rgba(54, 162, 235, 0.2)',
            whatsapp: 'rgba(255, 206, 86, 0.2)'
        },
        periods: {
            'weekly': { 
                count: 7, 
                format: 'ddd',
                xAxisLabel: 'Day',
                yAxisLabel: 'Performance',
                unit: 'days'
            },
            'monthly': { 
                count: 30, 
                format: 'DD MMM',
                xAxisLabel: 'Date',
                yAxisLabel: 'Performance',
                unit: 'days'
            },
            'yearly': { 
                count: 12, 
                format: 'MMM',
                xAxisLabel: 'Month',
                yAxisLabel: 'Performance',
                unit: 'months'
            }
        }
    };

    // Prepare property-specific data passed from backend
    const propertyData = {
        ref: "{{ $property->ref }}",
        displayDate: safeParseDate(rawDisplayDate), // Safely parsed display date
        viewTotal30Days: {{ $property_30days_view_total }},
        searchTotal30Days: {{ $property_30days_search_total }},
        enquiriesTotal30Days: {{ $total_past_30days }},
        whatsappClicks30Days: {{ $whatsappClickCount }}
    };

    // Enhanced data generation function with date-based filtering
    function generateMockData(period = 'weekly') {
        try {
            const labels = [];
            const viewData = [];
            const searchData = [];
            const enquiryData = [];
            const whatsappData = [];

            // Use property's display date as the reference point
            const propertyCreationDate = propertyData.displayDate;
            const config = CONFIG.periods[period];

            // Calculate base values for the period
            const periodConfig = {
                baseView: propertyData.viewTotal30Days / config.count,
                baseSearch: propertyData.searchTotal30Days / config.count,
                baseEnquiry: propertyData.enquiriesTotal30Days / config.count,
                baseWhatsapp: propertyData.whatsappClicks30Days / config.count
            };

            // Determine the starting point based on the period
            let startDate;
            switch(period) {
                case 'weekly':
                    startDate = moment(propertyCreationDate).subtract(6, 'days');
                    break;
                case 'monthly':
                    startDate = moment(propertyCreationDate).subtract(29, 'days');
                    break;
                case 'yearly':
                    startDate = moment(propertyCreationDate).subtract(11, 'months');
                    break;
            }

            for (let i = 0; i < config.count; i++) {
                // Generate labels based on the start date
                let currentDate;
                if (period === 'weekly' || period === 'monthly') {
                    currentDate = moment(startDate).add(i, 'days');
                } else {
                    currentDate = moment(startDate).add(i, 'months');
                }

                // Format label based on period
                const label = currentDate.format(config.format);
                labels.push(label);
                
                // Generate data with controlled randomness and trend
                const viewVariation = 0.3; // 30% variation
                const trendFactor = i / (config.count - 1); // Creates a gradual trend

                const views = Math.max(1, Math.floor(
                    periodConfig.baseView * (1 + (Math.random() - 0.5) * 2 * viewVariation) * (1 + trendFactor)
                ));
                
                const searches = Math.max(1, Math.floor(
                    periodConfig.baseSearch * (1 + (Math.random() - 0.5) * 2 * viewVariation) * (1 + trendFactor)
                ));
                
                const enquiries = Math.max(1, Math.floor(
                    periodConfig.baseEnquiry * (1 + (Math.random() - 0.5) * 2 * viewVariation) * (1 + trendFactor)
                ));
                
                const whatsapp = Math.max(1, Math.floor(
                    periodConfig.baseWhatsapp * (1 + (Math.random() - 0.5) * 2 * viewVariation) * (1 + trendFactor)
                ));

                viewData.push(views);
                searchData.push(searches);
                enquiryData.push(enquiries);
                whatsappData.push(whatsapp);
            }

            return { 
                labels, 
                viewData, 
                searchData, 
                enquiryData, 
                whatsappData 
            };
        } catch (error) {
            console.error('Error generating mock data:', error);
            return null;
        }
    }

    // Chart creation function
    function createPerformanceChart(data) {
        try {
            const ctx = document.getElementById('propertyPerformanceChart').getContext('2d');
            
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: `Property Views (Ref: ${propertyData.ref})`,
                            data: data.viewData,
                            borderColor: CONFIG.colors.views,
                            backgroundColor: CONFIG.colorsTransparent.views,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: `Property Searches (Ref: ${propertyData.ref})`,
                            data: data.searchData,
                            borderColor: CONFIG.colors.searches,
                            backgroundColor: CONFIG.colorsTransparent.searches,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: `Enquiries (Ref: ${propertyData.ref})`,
                            data: data.enquiryData,
                            borderColor: CONFIG.colors.enquiries,
                            backgroundColor: CONFIG.colorsTransparent.enquiries,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: `WhatsApp Clicks (Ref: ${propertyData.ref})`,
                            data: data.whatsappData,
                            borderColor: CONFIG.colors.whatsapp,
                            backgroundColor: CONFIG.colorsTransparent.whatsapp,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'nearest',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        title: {
                            display: true,
                            text: `Property Performance Analytics - Ref: ${propertyData.ref}`,
                            font: {
                                size: 16
                            },
                            align: 'center'
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.7)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            callbacks: {
                                label: function(context) {
                                    const datasetLabel = context.dataset.label || '';
                                    const dataPoint = context.parsed.y;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((dataPoint / total) * 100).toFixed(2);
                                    
                                    return `${datasetLabel}: ${dataPoint} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Performance Metrics',
                                align: 'center'
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)',
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Time Period',
                                align: 'center'
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)',
                                borderDash: [5, 5]
                            }
                        }
                    },
                    elements: {
                        line: {
                            borderWidth: 2
                        },
                        point: {
                            radius: 5,
                            hoverRadius: 7
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating chart:', error);
            return null;
        }
    }

    // KPI Calculation Function
    function calculateKPIs(data) {
        try {
            const kpiContainer = document.getElementById('analyticsKPIs');
            
            // Total calculations
            const totalViews = data.viewData.reduce((a, b) => a + b, 0);
            const totalSearches = data.searchData.reduce((a, b) => a + b, 0);
            const totalEnquiries = data.enquiryData.reduce((a, b) => a + b, 0);
            const totalWhatsapp = data.whatsappData.reduce((a, b) => a + b, 0);

            // KPI Calculations with safety checks
            const conversionRate = totalViews > 0 
                ? (totalEnquiries / totalViews * 100).toFixed(2) 
                : '0.00';
            
            const viewEnquiryRatio = totalViews > 0 
                ? (totalEnquiries / totalViews).toFixed(2) 
                : '0.00';
            
            const whatsappEngagement = totalViews > 0 
                ? (totalWhatsapp / totalViews * 100).toFixed(2) 
                : '0.00';
            
            const searchVisibility = totalViews > 0 
                ? (totalSearches / totalViews * 100).toFixed(2) 
                : '0.00';

            // KPI HTML Template
            kpiContainer.innerHTML = `
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-info" style="padding: 10px; border-radius: 10px;">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Conversion Rate (CR)</span>
                            <span class="info-box-number">${conversionRate}%</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${conversionRate}%"></div>
                            </div>
                            <span class="progress-description">Ref: ${propertyData.ref}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-success" style="padding: 10px; border-radius: 10px;">
                        <span class="info-box-icon"><i class="fas fa-eye"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">View to Enquiry Ratio</span>
                            <span class="info-box-number">${viewEnquiryRatio}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${viewEnquiryRatio * 100}%"></div>
                            </div>
                            <span class="progress-description">Ref: ${propertyData.ref}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-warning" style="padding: 10px; border-radius: 10px;">
                        <span class="info-box-icon"><i class="fas fa-phone"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">WhatsApp Engagement</span>
                            <span class="info-box-number">${whatsappEngagement}%</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${whatsappEngagement}%"></div>
                            </div>
                            <span class="progress-description">Ref: ${propertyData.ref}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-danger" style="padding: 10px; border-radius: 10px;">
                        <span class="info-box-icon"><i class="fas fa-search"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Search Visibility</span>
                            <span class="info-box-number">${searchVisibility}%</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${searchVisibility}%"></div>
                            </div>
                            <span class="progress-description">Ref: ${propertyData.ref}</span>
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('Error calculating KPIs:', error);
            document.getElementById('analyticsKPIs').innerHTML = 
                '<div class="col-12 text-center text-danger">Unable to calculate KPIs</div>';
        }
    }

    // Initial chart and KPI setup
    let currentPeriod = 'weekly';
    let currentChart;

    function initializeChart(period) {
        try {
            // Remove existing chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }

            // Generate mock data
            const performanceData = generateMockData(period);
            
            if (!performanceData) {
                throw new Error('Failed to generate performance data');
            }

            // Create new chart
            currentChart = createPerformanceChart(performanceData);
            
            if (!currentChart) {
                throw new Error('Failed to create performance chart');
            }

            // Calculate and display KPIs
            calculateKPIs(performanceData);
        } catch (error) {
            console.error('Chart initialization error:', error);
            document.getElementById('propertyPerformanceChart').innerHTML = 
                '<div class="text-center text-danger">Failed to load property analytics</div>';
        }
    }

    // Period selection event listeners
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('[data-period]').forEach(btn => 
                btn.classList.remove('active')
            );
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get selected period
            const selectedPeriod = this.getAttribute('data-period');
            
            // Reinitialize chart with new period
            initializeChart(selectedPeriod);
        });
    });

    // Initialize with weekly data
    initializeChart('weekly');
});
</script>
@endpush