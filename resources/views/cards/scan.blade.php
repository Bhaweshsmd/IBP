@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/assigncards.js') }}"></script>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Scan Cards') }}</h4>
        </div>
        <div class="card-body">
            
            <div id="qr-reader" style="width:500px"></div>
            <div id="qrreaderresults"></div>
            
            <script src="https://raw.githubusercontent.com/mebjas/html5-qrcode/master/minified/html5-qrcode.min.js"></script>
            <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
            <script>
                function docReady(fn) {
                    if (document.readyState === "complete"
                        || document.readyState === "interactive") {
                        setTimeout(fn, 1);
                    } else {
                        document.addEventListener("DOMContentLoaded", fn);
                    }
                }
            
                docReady(function () {
                    var lastResult, countResults = 0;
                    function onScanSuccess(decodedText, decodedResult) {
                        if (decodedText !== lastResult) {
                            ++countResults;
                            lastResult = decodedText;
                            console.log(`Scan result ${decodedText}`, decodedResult);
                            
                            var qrreaderresults = decodedText;
                            document.getElementById("qrreaderresults").innerHTML = qrreaderresults;
                        }
                    }
            
                    var html5QrcodeScanner = new Html5QrcodeScanner(
                        "qr-reader", { fps: 10, qrbox: 250 });
                    html5QrcodeScanner.render(onScanSuccess);
                });
            </script>

        </div>
    </div>
@endsection