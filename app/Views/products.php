<!DOCTYPE html>
<html>
<head>
    <title>Upload Gambar</title>
    <style>
        h2 { text-align: center; }

        form {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        input, button {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
        }

        button {
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .card {
            background: white;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }

        .card img {
            width: 100%;
            border-radius: 8px;
        }

        .actions button {
            width: 48%;
            margin-top: 5px;
        }

        .delete { background: red; }
        .edit { background: orange; }
    </style>
</head>
<body>

<h2>Upload Gambar</h2>

<form id="uploadForm">
    <input type="text" name="title" placeholder="Judul"><br><br>
    <input type="file" name="image"><br><br>
    <button type="submit">Upload</button>
</form>


<h3>List Gambar</h3>
<div id="list"></div>


<h3>Edit</h3>
<form id="editForm">
    <input type="hidden" id="editId">
    <input type="text" name="title" id="editTitle"><br><br>
    <input type="file" name="image"><br><br>
    <button type="submit">Update</button>
</form>



<script>
document.getElementById("uploadForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const res = await fetch("http://localhost:8080/api/images", {
        method: "POST",
        body: formData
    });

    const data = await res.json();
    console.log(data);

    if (res.ok) {
        alert("Upload berhasil!");
    } else {
        alert(data.message || "Gagal upload!");
    }
});
async function loadData() {
    const res = await fetch("http://localhost:8080/api/images");
    const data = await res.json();

    let html = "";

    data.forEach(item => {
        html += `
            <div>
                <p>${item.nama}</p>
                <img src="http://localhost:8080/uploads/${item.deskripsi}" width="100">
                <br>
                <button onclick="hapus(${item.id})">Hapus</button>
                <button onclick="edit(${item.id}, '${item.nama}')">Edit</button>
                <hr>
            </div>
        `;
    });

    document.getElementById("list").innerHTML = html;
}
loadData();

async function hapus(id) {
    if (!confirm("Yakin hapus?")) return;

    await fetch(`http://localhost:8080/api/images/${id}`, {
        method: "DELETE"
    });

    alert("Terhapus!");
    loadData();
}

function edit(id, nama) {
    document.getElementById("editId").value = id;
    document.getElementById("editTitle").value = nama;
}

document.getElementById("editForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const id = document.getElementById("editId").value;
    const formData = new FormData(this);

    await fetch(`http://localhost:8080/api/images/update/${id}`, {
        method: "POST",
        body: formData
    });

    alert("Updated!");
    loadData();
});
</script>

</body>
</html>