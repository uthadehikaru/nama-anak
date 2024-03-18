<?php
$data = null;
$list = [];
$nama = "";
if(isset($_GET['nama'])){
    $db = new SQLite3('nama.db');

    $nama = $_GET['nama'];
    if($nama=='random'){
        $data = $db->querySingle("select id,name,type,gender,description from data order by random() limit 1", true);
        $url = "/index.php?nama=random";
    }else{
        $stmt = $db->prepare("select id,name,type,gender,description from data where name like :name order by random() limit 12");
        $stmt->bindValue(':name', '%'.$nama.'%', SQLITE3_TEXT);
        $result = $stmt->execute();
        while ($res = $result->fetchArray())
            $list[] = $res;
        $url = "/index.php?nama=".$nama;
    }
}
if(isset($_GET['words'])){
    $db = new SQLite3('nama.db');

    $words = $_GET['words'] ?? 1;
    $stmt = $db->prepare("select id,name,type,gender,description from data order by random() limit $words");
    $result = $stmt->execute();
    $data['name'] = "";
    $data['gender'] = "";
    $data['type'] = "";
    $data['description'] = "";
    while($res  = $result->fetchArray()){
        $data['name'] .= $res['name']. " ";
        $data['gender'] = $res['gender'];
        $data['type'] .= $res['type']. " ";
        $data['description'] .= $res['description']. " ";
    }
    $url = "/index.php?words=".$words;
}
?>
<html>
<head>
    <title>Nama Anak</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="navbar bg-base-100">
        <div class="flex-1">
            <a href="/" class="btn btn-ghost text-xl">Nama Anak</a>
        </div>
        <div class="flex-none">
            <form class="form-control mb-0" method="GET" action="index.php">
                <input type="text" name="nama" placeholder="Cari Nama" value="<?php echo $nama; ?>"
                class="input input-bordered w-24 md:w-auto" required /> 
                <button type="submit"></button>
            </form>
            <ul class="menu menu-horizontal px-1">
                <li><a href="/index.php?nama=random">Acak</a></li>
                <li><a href="/index.php?words=2">2 Kata</a></li>
                <li><a href="/index.php?words=3">3 Kata</a></li>
            </ul>
        </div>
    </div>
    <?php if($data): ?>
    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content text-center">
            <div class="w-full">
                <h1 class="text-5xl font-bold"><?php echo $data['name'] ?></h1>
                <p class="py-2"><?php echo $data['description'] ?></p>
                <p class="pb-6 italic text-xs"><?php echo $data['gender'] ?> - <?php echo $data['type'] ?></p>
                <a href="<?php $url ?>" class="btn btn-primary">Cari Lagi</a>
            </div>
        </div>
    </div>
    <?php elseif(count($list)>0): ?>
    <div class="grid grid-cols-4 gap-4 p-2 mb-4">
        <?php foreach($list as $data): ?>
            <div class="card bg-primary text-primary-content hover:opacity-75">
                <div class="card-body">
                    <h2 class="card-title"><?php echo $data['name']; ?></h2>
                    <p class="py-2"><?php echo $data['description'] ?></p>
                    <p class="italic text-xs"><?php echo $data['gender'] ?> - <?php echo $data['type'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content text-center">
            <div class="max-w-md">
                <h1 class="text-5xl font-bold">Selamat Datang</h1>
                <p class="py-6">website ini berisi daftar referensi untuk nama anak anda</p>
                <a href="/index.php?nama=random" class="btn btn-primary">Cari Nama</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <footer class="footer footer-center p-4 bg-base-300 text-base-content">
        <aside>
            <p>Copyright © <?php echo date('Y'); ?> - All right reserved by <a href="https://zuhriutama.com">zuhriutama.com</a></p>
        </aside>
    </footer>
</body>

</html>