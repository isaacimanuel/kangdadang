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
    function addRow() {
        var table = document.getElementById("rencana").getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);
        var cols = 8; // Jumlah kolom
        var newNumber = table.rows.length;

        for (var i = 0; i < cols; i++) {
            var cell = newRow.insertCell(i);
            var input = document.createElement("input");
            input.type = (i === 0 || i === 3 || i === 4 || i === 5) ? "number" : "text";
            input.className = "form-control small-placeholder";

            if (i === 0) {
                input.value = newNumber;
                input.readOnly = true;
                input.name = "no[]";
            } else if (i === 1) {
                input.name = "jenis[]";
            } else if (i === 2) {
                input.name = "satuan[]";
            } else if (i === 3) {
                input.name = "jumlah[]";
            } else if (i === 4) {
                input.name = "harga[]";
            } else if (i === 5) {
                input.name = "kurs[]";
            } else if (i === 6) {
                input.name = "kode[]";
            } else if (i === 7) {
                input.name = "sub[]";
            }

            cell.appendChild(input);
        }

        var cell = newRow.insertCell(cols);
        var deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.className = "btn btn-danger";
        var icon = document.createElement("i");
        icon.className = "fas fa-minus";
        deleteButton.appendChild(icon);
        deleteButton.onclick = function() {
            deleteRow(this);
            updateRowNumbers();
        }
        cell.appendChild(deleteButton);
    }

    function deleteRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
        updateRowNumbers();
    }

    function updateRowNumbers() {
        var table = document.getElementById("rencana").getElementsByTagName('tbody')[0];
        for (var i = 0; i < table.rows.length; i++) {
            table.rows[i].cells[0].getElementsByTagName('input')[0].value = i + 1;
        }
    }
</script>