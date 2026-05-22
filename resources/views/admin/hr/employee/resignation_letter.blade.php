<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resignation Letter - {{ $resignation->employee->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Herr+Von+Muellerhoff&display=swap" rel="stylesheet">
    <style>
        .font-signature {
            font-family: 'Herr Von Muellerhoff', cursive;
        }

        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen p-8 flex items-center justify-center">
        <div class="bg-white shadow-2xl max-w-3xl w-full border-8 border-black p-16">

            <!-- Header -->
            <h1 class="text-4xl font-bold text-center mb-12 tracking-wide">
                LETTER OF RESIGNATION
            </h1>

            <!-- Employee Info -->
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-2">{{ strtoupper($resignation->employee->name) }}</h2>
                <p class="text-gray-700">
                    {{ $resignation->employee->address != 'No Address' ? $resignation->employee->address : '123 Anywhere St.' }},
                </p>
            </div>

            <!-- Subject -->
            <div class="mb-6">
                <p class="font-bold">Subject: Resignation</p>
            </div>

            <!-- Body -->
            <div class="mb-6">
                <p class="mb-4">Dear Manager,</p>

                @php
                    $createdDate = \Carbon\Carbon::parse($resignation->employee->created_at);
                    $currentDate = \Carbon\Carbon::now();
                    $yearsOfService = $currentDate->diffInYears($createdDate);
                    $yearsOfService = $yearsOfService > 0 ? $yearsOfService : 1;
                @endphp

                <p class="mb-4 leading-relaxed ">
                    {{ $resignation->reason }}
                </p>

                <p class="mb-2">All the best,</p>
                <p class="mb-8">{{ $resignation->employee->name }}</p>
            </div>

            <!-- Signature -->
            <div class="mb-8">
                <div class="font-signature text-5xl">
                    {{ explode(' ', $resignation->employee->name)[0] }}
                </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-12 pt-6 border-t border-gray-300 text-sm text-gray-600">
                <div class="flex justify-between">
                    <div>
                        <p><span class="font-semibold">Resignation Date:</span>
                            {{ \Carbon\Carbon::parse($resignation->resignation_date)->format('F d, Y') }}</p>
                        <p><span class="font-semibold">Status:</span>
                            <span
                                class="px-2 py-1 rounded 
                                @if ($resignation->status == 'Accepted') bg-green-100 text-green-800
                                @elseif($resignation->status == 'Pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $resignation->status }}
                            </span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p><span class="font-semibold">Email:</span> {{ $resignation->employee->email }}</p>
                        <p><span class="font-semibold">Phone:</span> {{ $resignation->employee->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Print Button -->
            <div class="mt-8 text-center no-print">
                <button onclick="window.print()"
                    class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition">
                    Print Resignation Letter
                </button>
            </div>

        </div>
    </div>
</body>

</html>
