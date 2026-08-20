<?php include 'templates/header.php'; ?>
<div class="card p-5 text-center">
    <h2>Internal Records Search</h2>
    <form action="dashboard.php" method="GET" class="mt-3">
        <input type="text" name="id" class="form-control mb-3" placeholder="Enter Record ID (e.g. 100)">
        <button class="btn btn-dark w-100">Search</button>
    </form>
</div>
<?php include 'templates/footer.php'; ?>
