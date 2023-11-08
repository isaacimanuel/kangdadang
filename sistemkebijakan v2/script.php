<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../js/scripts.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="../assets/demo/datatables-demo.js"></script>
<!-- button go to top -->
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
<!-- search kategori -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        var input, filter, cards, cardContainer, title, i, noDataMessage;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        cardContainer = document.getElementById('cardContainer');
        cards = cardContainer.getElementsByClassName('col-md-2'); // Ubah ini sesuai dengan kelas kolom kartu
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
<!-- search produk -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        var input, filter, cards, cardContainer, title, i, noDataMessage;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        cardContainer = document.querySelector('.row'); // Ubah selector ini sesuai dengan kontainer kartu Anda
        cards = cardContainer.getElementsByClassName('col-md-2');
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
<!-- search katalog -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('input', function() {
            var keyword = this.value.toLowerCase();
            var cards = document.getElementsByClassName('card');

            for (var i = 0; i < cards.length; i++) {
                var cardTitle = cards[i].querySelector('.card-title').innerText.toLowerCase();
                var cardText = cards[i].querySelector('.card-text').innerText.toLowerCase();

                if (cardTitle.includes(keyword) || cardText.includes(keyword)) {
                    cards[i].style.display = 'flex';
                } else {
                    cards[i].style.display = 'none';
                }
            }

            var noDataMessage = document.getElementById('noDataMessage');
            if (Array.from(cards).every(card => card.style.display === 'none')) {
                noDataMessage.style.display = 'block';
            } else {
                noDataMessage.style.display = 'none';
            }
        });
    });
</script>
<!-- reset filter -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('resetFilter').addEventListener('click', function() {
            document.getElementById('barang').value = '';
            document.getElementById('vendor').value = '';
            document.getElementById('merk').value = '';
            document.getElementById('min_harga').value = '';
            document.getElementById('max_harga').value = '';
        });
    })
</script>
<!-- dropdown kategori dan tipe di tambah barang vendor -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ketika tipe produk berubah
        document.getElementById("idtipe").addEventListener("change", function() {
            var idtipe = this.value;

            // Bersihkan opsi produk yang ada
            var idprodukSelect = document.getElementById("idproduk");
            idprodukSelect.innerHTML = "";

            // Jika tidak ada tipe yang dipilih, keluar
            if (!idtipe) return;

            // Ambil daftar produk dari database berdasarkan tipe yang dipilih
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_produk_by_tipe.php?idtipe=" + idtipe, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);

                        // Tambahkan opsi produk ke select
                        for (var i = 0; i < data.length; i++) {
                            var option = document.createElement("option");
                            option.value = data[i].idproduk;
                            option.text = data[i].namaproduk;
                            idprodukSelect.appendChild(option);
                        }
                    } else {
                        console.error("Error:", xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send();
        });
    });
</script>
<!-- hover vendor dan merk -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var cards = document.querySelectorAll('.card');

        cards.forEach(function(card) {
            card.addEventListener('mouseenter', function() {
                card.querySelector('.merk', this).style.display = 'none';
                card.querySelector('.vendor', this).style.display = 'block';
            });

            card.addEventListener('mouseleave', function() {
                card.querySelector('.merk', this).style.display = 'block';
                card.querySelector('.vendor', this).style.display = 'none';
            });
        });
    });
</script>
<!-- pilih vendor -->
<script>
    document.getElementById('selectVendor').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var idVendor = selectedOption.dataset.idvendor;
        document.getElementById('idvendor').value = idVendor;
    });
</script>