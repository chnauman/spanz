@extends('layouts.admin')

@section('title', 'Edit Tender View Pricing Rule')

@section('content')
<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="w-full">
        <div class="border border-gray-300 p-3 sm:p-4 lg:p-6 bg-white rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-sm bg-[#092C48] text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">Edit Pricing Rule</h1>
                <a href="{{ route('admin.tender-view-pricing.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 transition-colors">
                    Back
                </a>
            </div>

            @if($errors->any())
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.tender-view-pricing.update', $rule) }}" method="POST" class="mt-6">
                @csrf
                @method('PUT')

                @include('admin.tender-view-pricing._form', ['rule' => $rule])

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.tender-view-pricing.index') }}"
                       class="bg-gray-600 text-white px-6 py-2 rounded text-sm hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                        Update Rule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

