<!DOCTYPE html>
<html>
<head>
    <title>Upload Gambar</title>
</head>
<body>

<h2>Upload Gambar</h2>

<form id="uploadForm">
    <input type="text" name="title" placeholder="Judul"><br><br>
    <input type="file" name="image"><br><br>
    <button type="submit">Upload</button>
</form>

<script>
document.getElementById("uploadForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const res = await fetch("http://localhost:8080/products", {
        method: "POST",
        body: formData
    });

    const data = await res.json();

    console.log(data);

    if (res.ok) {
        alert("Upload berhasil!");
    } else {
        alert("Gagal upload!");
    }
});
</script>

</body>
</html>