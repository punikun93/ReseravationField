@if(session('success'))
    <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 relative" role="alert">
        <p>{{ session('success') }}</p>
        <button onclick="closeAlert('success-alert')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414l2.935-2.934L5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 111.414 1.414l-2.935 2.934 2.935 2.935a1 1 0 010 1.414z"/></svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div id="error-alert" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
        <p>{{ session('error') }}</p>
        <button onclick="closeAlert('error-alert')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414l2.935-2.934L5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 111.414 1.414l-2.935 2.934 2.935 2.935a1 1 0 010 1.414z"/></svg>
        </button>
    </div>
@endif

@if($errors->any())
    <div id="error-list-alert" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button onclick="closeAlert('error-list-alert')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414l2.935-2.934L5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 111.414 1.414l-2.935 2.934 2.935 2.935a1 1 0 010 1.414z"/></svg>
        </button>
    </div>
@endif

<script>
    function closeAlert(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
