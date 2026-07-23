@extends('layout.index')
@extends('front.nav')

@section('home')
    {{-- Search customer details --}}
    <div class="w-full h-[80px] flex justify-center items-center bg-[#1D4ED8]">
        <input type="text" name="" onkeyup="searchTable()" id="searchInput" placeholder="Search Customer"
            class="w-[50%] py-2 px-3 outline-none border-0 rounded">
    </div>
    {{-- Search customer details --}}


    <div class="w-full mx-auto mt-3 mb-5 overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-200 mx-auto">
            @if (session('success'))
                <div class="alert alert-success text-center" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border border-gray-300">S.NO</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER NAME</th>
                    <th class="px-4 py-2 border border-gray-300">PHONE NUMBER</th>
                    <th class="px-4 py-2 border border-gray-300">STATUS</th>
                    <th class="px-4 py-2 border border-gray-300 hidden" id="heading">PRICE</th>
                    <th class="px-4 py-2 border border-gray-300">REMARKS</th>
                    <th class="px-4 py-2 border border-gray-300">AGENT NAME</th>
                    <th class="px-4 py-2 border border-gray-300">EXPIRY DATE</th>
                    <th class="px-4 py-2 border border-gray-300">ACTION</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- Data rows will be appended here -->
            </tbody>
        </table>

    </div>
    {{-- Show Customer Details --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // show customer number in to table 

        $(document).ready(function() {
            let currentPage = 1;
            let perPage = 100; // Ek page me 50 records
            let isLoading = false;
            let searchQuery = '';

            function fetchCustomerNumbers(page = 1, search = '') {
                if (isLoading) return;
                isLoading = true;

                // Loading indicator
                if (page === 1) {
                    $('#tableBody').html(`
                <tr>
                    <td colspan="9" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-gray-600">Loading data...</p>
                    </td>
                </tr>
            `);
                }

                $.ajax({
                    url: "{{ route('getAllCallingNumbers') }}",
                    method: 'GET',
                    data: {
                        page: page,
                        per_page: perPage,
                        search: search
                    },
                    success: function(response) {
                        let tableBody = $('#tableBody');

                        // Clear previous data on first page
                        if (page === 1) {
                            tableBody.empty();
                        }

                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(customer, index) {
                                let rowNumber = (response.current_page - 1) * response
                                    .per_page + index + 1;
                                let rowHtml = generateRowHtml(customer, rowNumber);
                                tableBody.append(rowHtml);
                            });

                            // Attach all event listeners
                            attachEventListeners();

                            // Show pagination
                            renderPagination(response);

                        } else if (page === 1) {
                            tableBody.html(`
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-inbox text-4xl mb-2"></i>
                                <p>No calling numbers found</p>
                            </td>
                        </tr>
                    `);
                            $('.pagination-container').remove();
                        }

                        isLoading = false;
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        $('#tableBody').html(`
                    <tr>
                        <td colspan="9" class="text-center py-8 text-red-500">
                            <i class="fa-solid fa-exclamation-triangle text-4xl mb-2"></i>
                            <p>Error loading data. Please refresh the page.</p>
                        </td>
                    </tr>
                `);
                        isLoading = false;
                    }
                });
            }

            function generateRowHtml(customer, rowNumber) {
                return `
            <tr class="customer-row odd:bg-gray-50 even:bg-white hover:bg-blue-50 transition-colors" data-id="${customer.id}">
                <td class="px-4 py-2 border border-gray-300 text-center font-medium">${rowNumber}</td>
                <td class="px-4 py-2 border border-gray-300">
                    ${customer.status === 'pending' ? 
                        `<input type="text" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400" 
                                             placeholder="Enter Customer Name" name="customer_name">` : 
                        `<span class="font-medium">${customer.customer_name || '-'}</span>`}
                </td>
                <td class="px-4 py-2 border border-gray-300">
                    <div class="flex items-center gap-2">
                        <span class="number font-mono">${customer.customer_number}</span>
                        <button class="copy px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                    ${customer.status === 'pending' ? 
                        `<select class="form-select status-select w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400" 
                                             name="status" required>
                                                <option value="">-- Select Status --</option>
                                                <option value="vm">VM</option>
                                                <option value="not int">Not Interested</option>
                                                <option value="hung up">Hung Up</option>
                                                <option value="not ava">Not Available</option>
                                                <option value="not in service">Not In Service</option>
                                                <option value="call back">Call Back</option>
                                                <option value="lead">Lead</option>
                                                <option value="trial">ON Trial</option>
                                            </select>` : 
                        getStatusHtml(customer.status)}
                </td>
                <td class="px-0 py-2 border border-gray-300 hidden price-column">
                    ${customer.status === 'pending' ? 
                        `<input type="hidden" class="form-control price-input w-full px-3 py-2 border border-gray-300 rounded-md" 
                                             placeholder="Enter Price" name="price" min="0" step="0.01">` : 
                        `<span class="text-gray-700 font-medium">${customer.price ? '$' + customer.price : 'N/A'}</span>`}
                </td>
                <td class="px-2 py-2 border border-gray-300">
                    ${!customer.remarks || customer.remarks === '' ? 
                        `<textarea name="remarks" cols="20" rows="2" placeholder="Enter Remarks"
                                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                                             required></textarea>` : 
                        `<span class="text-gray-700">${customer.remarks}</span>`}
                </td>
                <td class="px-4 py-2 border border-gray-300">
                    <span class="text-gray-700">${customer.user?.name || '-'}</span>
                </td>
                <td class="px-4 py-2 border border-gray-300 text-center">
                    <span class="text-gray-600">${new Date(customer.date).toLocaleDateString('en-GB', {
                        day: '2-digit', 
                        month: 'short', 
                        year: 'numeric'
                    })}</span>
                </td>
                <td class="px-4 py-2 border border-gray-300 text-center">
                    ${customer.status === 'pending' ? 
                        `<button type="button" class="add-customer-data px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                                                <i class="fa-solid fa-plus mr-1"></i> Add
                                            </button>` : 
                        `<a href="/${customer.id}/edit-customer-numbers" 
                                                class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                            </a>`}
                </td>
            </tr>`;
            }

            function attachEventListeners() {
                // Add Customer Button
                $('.add-customer-data').off('click').on('click', function(e) {
                    e.preventDefault();

                    const button = $(this);
                    const row = button.closest('.customer-row');
                    const customerId = row.data('id');

                    // Validation
                    const customerName = row.find('[name="customer_name"]').val();
                    const status = row.find('[name="status"]').val();
                    const remarks = row.find('[name="remarks"]').val();
                    const price = row.find('[name="price"]').val();

                    if (!status || !remarks) {
                        alert('Please fill all required fields!');
                        return;
                    }

                    if ((status === 'lead' || status === 'trial') && !price) {
                        alert('Please enter price for Lead/Trial status!');
                        return;
                    }

                    // Disable button
                    button.prop('disabled', true).html(
                        '<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

                    const formData = new FormData();
                    formData.append('customer_name', customerName);
                    formData.append('status', status);
                    formData.append('price', price);
                    formData.append('remarks', remarks);

                    fetch(`/${customerId}/storeCustomerNumbersDetails`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Show success message
                            alert(data.message || 'Customer details saved successfully!');
                            window.location.reload();
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Something went wrong! Please try again.');
                            button.prop('disabled', false).html('<i class="fa-solid fa-plus"></i> Add');
                        });
                });

                // Copy Number (Modern Clipboard API)
                $('.copy').off('click').on('click', function(e) {
                    e.preventDefault();
                    const button = $(this);
                    const number = button.siblings('.number').text();

                    navigator.clipboard.writeText(number).then(() => {
                        button.html('<i class="fa-solid fa-check"></i>').removeClass('bg-blue-600')
                            .addClass('bg-green-600');
                        setTimeout(() => {
                            button.html('<i class="fa-regular fa-copy"></i>').removeClass(
                                'bg-green-600').addClass('bg-blue-600');
                        }, 1500);
                    }).catch(err => {
                        console.error('Copy failed:', err);
                        alert('Failed to copy number');
                    });
                });

                // Status Change - Show/Hide Price Input
                $('.status-select').off('change').on('change', function() {
                    const row = $(this).closest('tr');
                    const priceColumn = row.find('.price-column');
                    const priceInput = row.find('.price-input');
                    const heading = $('#heading');

                    if (this.value === 'lead' || this.value === 'trial') {
                        heading.removeClass('hidden');
                        priceColumn.removeClass('hidden');
                        priceInput.attr('type', 'number').attr('required', true);
                    } else {
                        priceColumn.addClass('hidden');
                        priceInput.val('').attr('type', 'hidden').removeAttr('required');

                        // Hide heading if no price columns are visible
                        if ($('.price-column:visible').length === 0) {
                            heading.addClass('hidden');
                        }
                    }
                });
            }

            function renderPagination(response) {
                const {
                    current_page,
                    last_page,
                    from,
                    to,
                    total
                } = response;

                if (last_page <= 1) {
                    $('.pagination-container').remove();
                    return;
                }

                let paginationHtml = `
            <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4 px-4">
                <div class="text-gray-600">
                    Showing <span class="font-semibold">${from}</span> to 
                    <span class="font-semibold">${to}</span> of 
                    <span class="font-semibold">${total}</span> records
                </div>
                <div class="flex items-center gap-2">`;

                // Previous button
                if (current_page > 1) {
                    paginationHtml += `
                <button class="pagination-btn px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors" 
                        data-page="${current_page - 1}">
                    <i class="fa-solid fa-chevron-left"></i> Previous
                </button>`;
                }

                // Page numbers
                let startPage = Math.max(1, current_page - 2);
                let endPage = Math.min(last_page, current_page + 2);

                if (startPage > 1) {
                    paginationHtml += `
                <button class="pagination-btn px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors" 
                        data-page="1">1</button>`;
                    if (startPage > 2) {
                        paginationHtml += `<span class="px-2 text-gray-500">...</span>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    let activeClass = i === current_page ?
                        'bg-blue-800 font-bold' :
                        'bg-blue-500 hover:bg-blue-600';
                    paginationHtml += `
                <button class="pagination-btn px-4 py-2 ${activeClass} text-white rounded transition-colors" 
                        data-page="${i}">${i}</button>`;
                }

                if (endPage < last_page) {
                    if (endPage < last_page - 1) {
                        paginationHtml += `<span class="px-2 text-gray-500">...</span>`;
                    }
                    paginationHtml += `
                <button class="pagination-btn px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors" 
                        data-page="${last_page}">${last_page}</button>`;
                }

                // Next button
                if (current_page < last_page) {
                    paginationHtml += `
                <button class="pagination-btn px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors" 
                        data-page="${current_page + 1}">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </button>`;
                }

                paginationHtml += `
                </div>
            </div>`;

                // Remove old pagination
                $('.pagination-container').remove();

                // Add new pagination
                $('table').parent().after(`<div class="pagination-container">${paginationHtml}</div>`);

                // Pagination click event
                $('.pagination-btn').on('click', function() {
                    const page = $(this).data('page');
                    currentPage = page;

                    // Smooth scroll to top
                    $('html, body').animate({
                        scrollTop: 0
                    }, 300);

                    fetchCustomerNumbers(page, searchQuery);
                });
            }

            function getStatusHtml(status) {
                const statusConfig = {
                    'call back': {
                        class: 'bg-yellow-500',
                        icon: 'fa-phone'
                    },
                    'not int': {
                        class: 'bg-red-600',
                        icon: 'fa-times-circle'
                    },
                    'vm': {
                        class: 'bg-gray-700',
                        icon: 'fa-voicemail'
                    },
                    'hung up': {
                        class: 'bg-blue-600',
                        icon: 'fa-phone-slash'
                    },
                    'not ava': {
                        class: 'bg-gray-500',
                        icon: 'fa-user-slash'
                    },
                    'not in service': {
                        class: 'bg-cyan-600',
                        icon: 'fa-signal'
                    },
                    'lead': {
                        class: 'bg-green-600',
                        icon: 'fa-star'
                    },
                    'trial': {
                        class: 'bg-indigo-600',
                        icon: 'fa-flask'
                    },
                };

                const config = statusConfig[status] || {
                    class: 'bg-gray-500',
                    icon: 'fa-question'
                };

                return `
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-white text-sm font-medium ${config.class}">
                <i class="fa-solid ${config.icon}"></i>
                ${status}
            </span>`;
            }

            // Search with debounce (500ms delay)
            let searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchQuery = $(this).val();

                searchTimeout = setTimeout(() => {
                    currentPage = 1;
                    fetchCustomerNumbers(1, searchQuery);
                }, 500);
            });

            // Initial load
            fetchCustomerNumbers(1);
        });
    </script>
@endsection
