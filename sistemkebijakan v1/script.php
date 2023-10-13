<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../js/scripts.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="../assets/demo/datatables-demo.js"></script>
<script>
    var mybutton = document.getElementById("myBtn");
    window.onscroll = function() {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        var input, filter, cards, cardContainer, title, i, noDataMessage;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        cardContainer = document.getElementsByClassName('card-columns')[0];
        cards = cardContainer.getElementsByClassName('card');
        noDataMessage = document.getElementById('noDataMessage'); // ID pesan "Tidak ada data"

        var found = false; // Variabel untuk melacak apakah ada data yang ditemukan

        for (i = 0; i < cards.length; i++) {
            title = cards[i].querySelector('.card-title');
            if (title.innerText.toUpperCase().indexOf(filter) > -1) {
                cards[i].style.display = '';
                found = true; // Data ditemukan
            } else {
                cards[i].style.display = 'none';
            }
        }

        // Tampilkan pesan "Tidak ada data" jika tidak ada data yang ditemukan
        if (!found) {
            noDataMessage.style.display = 'block';
        } else {
            noDataMessage.style.display = 'none';
        }
    });
</script>
<script>
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('barang').value = '';
        document.getElementById('vendor').value = '';
        document.getElementById('min_harga').value = '';
        document.getElementById('max_harga').value = '';
    });
</script>