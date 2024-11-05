<html>
  <body>
    <button id="pay-button">Pay!</button>
    <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre> 

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
          snap.pay('{{ $snap_token }}', {
            onSuccess: function(result){
              document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            onPending: function(result){
              document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            onError: function(result){
              document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            }
          });
        };
    </script>
  </body>
</html>
