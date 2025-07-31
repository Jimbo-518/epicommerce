<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <link rel="icon" href="">

  <title>Buscador</title>

  <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">

  <script src="<?= BASE_URL ?>assets/js/ie-emulation-modes-warning.js.descarga"></script>
  <style>
    body {
      text-align: center;
      align-items: center;
      align-content: center;
    }

    .search {
      width: 100%;
      position: relative;
      display: flex;
      justify-content: center;
      margin-top: 50px;
    }

    .searchTerm {
      width: 80%;
      border: 2px solid #B0B0B0;
      border-radius: 5px 0 0 5px;
      padding: 10px;
      height: 40px;
      outline: none;
      font-size: 16px;
      color: #555;
      background-color: #fff;
    }

    .searchTerm:focus {
      border-color: 2px solidrgb(79, 81, 82);
      color: rgb(87, 90, 90);
    }

    .searchButton {
      width: 50px;
      height: 40px;
      border: 2px solidrgb(79, 81, 82);
      background: rgb(111, 112, 112);
      text-align: center;
      color: #fff;
      border-radius: 0 5px 5px 0;
      cursor: pointer;
      font-size: 18px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .searchButton:hover {
      background: rgb(87, 90, 90);
    }

    .wrap {
      display: inline-flex;
      justify-content: center;
      align-items: center;
    }

    .read-to-me2bh0oahaee1-hover {
      background-color: #FFB77D !important;
      color: #000 !important;
      border-radius: 4px !important;
      box-decoration-break: clone !important;
      -webkit-box-decoration-break: clone !important;
      cursor: pointer !important;
      transition: .3s background-color !important;
    }

    .read-to-me2bh0oahaee1-highlight {
      background-color: #FFDBBE !important;
      color: #000 !important;
      border-radius: 4px !important;
      box-decoration-break: clone !important;
      -webkit-box-decoration-break: clone !important;
      transition: .3s background-color !important;
    }

    .read-to-me2bh0oahaee1-floating-loader {
      position: absolute;
      width: 30px;
      height: 30px;
      background: url('chrome-extension://anpkfnccdmljhdegcaonjffhjhhcalaj/icons/icon-loader-orange.svg') no-repeat center/cover;
      animation: loading 1.1s linear infinite;
      z-index: 100000;
    }

    .read-to-me2bh0oahaee1-highlight * {
      color: #000 !important;
    }

    @keyframes loading {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    table {
      width: 100%;
      max-width: 900px;
      margin: 20px auto;
      border-collapse: collapse;
      font-size: 16px;
      text-align: center;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    table th,
    table td {
      border: 1px solid #dddddd;
      padding: 12px;
    }

    table th {
      background-color: #f4f4f4;
      font-weight: bold;
      color: #555;
    }

    table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    table tr:hover {
      background-color:rgb(186, 226, 252);
    }

    table caption {
      margin-bottom: 10px;
      font-size: 20px;
      font-weight: bold;
      color: #333;
    }
  </style>
</head>

<body>

  <?php require_once '../app/views/templates/header.php'; ?>

  <div class="class=col-sm-12 col-md-12 col-xs-12">
    <div class="row">
      <h1 class="page-header">Buscador de Prendas</h1>

      <div class="wrap col-sm-12 col-md-12 col-xs-12">
        <form action="<?= BASE_URL ?>buscador/buscar" method="POST">
          <input type="text" id="model" name="model" class="searchTerm col-sm-12 col-md-12 col-xs-12"
            placeholder="Ingrese el modelo" list="modellist" required>
          <datalist id="modellist">
            <?php foreach ($modelos as $modelo): ?>
              <?php if (isset($modelo['Modelo'])): ?>
                <?php $valor = is_string($modelo['Modelo']) ? $modelo['Modelo'] : json_encode($modelo['Modelo']); ?>
                <option value="<?= htmlspecialchars($valor) ?>"></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </datalist>
          <button type="submit" class="searchButton col-sm-12 col-md-12 col-xs-12">🔍</button>
        </form>
      </div>
      <br><br>

      <?php if (isset($prendas) && count($prendas) > 0): ?>
        <table>
          <tr>
            <th>UPC</th>
            <th>Parte</th>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Marbete</th>
            <th>Cantidad</th>
            <th>Acción</th>
          </tr>
          <?php foreach ($prendas as $prenda): ?>
            <?php if (!empty($prenda['stock'])): ?>
              <?php foreach ($prenda['stock'] as $stock): ?>
                <tr>
                  <td><?= htmlspecialchars($prenda['UPC']) ?></td>
                  <td><?= htmlspecialchars($prenda['Parte']) ?></td>
                  <td><?= htmlspecialchars($prenda['Descripción']) ?></td>
                  <td><?= htmlspecialchars($prenda['Marca']) ?></td>
                  <td><?= htmlspecialchars($stock['Marbete']) ?></td>
                  <td><?= htmlspecialchars($stock['Cantidad']) ?></td>
                  <td>
                    <button type="button" class="descuento-btn" data-marbete="<?= htmlspecialchars($stock['Marbete']) ?>"
                      data-upc="<?= htmlspecialchars($prenda['UPC']) ?>" <?= ($stock['Cantidad'] == 0) ? 'disabled' : '' ?>>🏷️
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td><?= htmlspecialchars($prenda['UPC']) ?></td>
                <td><?= htmlspecialchars($prenda['Parte']) ?></td>
                <td><?= htmlspecialchars($prenda['Descripción']) ?></td>
                <td><?= htmlspecialchars($prenda['Marca']) ?></td>
                <td colspan="3">❌ No hay stock disponible</td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </table>
      <?php elseif (isset($modelo)): ?>
        <p>
          No se encontraron resultados para el modelo:
          <strong>
            <?=isset($modelo['Modelo']) && is_string($modelo['Modelo']) ? htmlspecialchars($modelo['Modelo']) : 'Desconocido' ?>
          </strong>
        </p> <?php endif; ?>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".descuento-btn").forEach(button => {
        button.addEventListener("click", function () {
          let marbete = this.getAttribute("data-marbete");
          let upc = this.getAttribute("data-upc");

          let cantidad = prompt(`¿Cuántas prendas deseas descontar del marbete: ${marbete}?`, "1");

          if (cantidad !== null && !isNaN(cantidad) && parseInt(cantidad) > 0) {
            if (confirm(`¿Seguro que deseas descontar ${cantidad} prenda(s)?`)) {
              fetch("<?= BASE_URL ?>buscador/descontar", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `marbete=${marbete}&upc=${upc}&cantidad=${cantidad}&name=${name}`
              })
                .then(response => response.text())
                .then(data => {
                  alert(data);
                  location.reload();
                })
                .catch(error => console.error("Error:", error));
            }
          } else {
            alert("Por favor ingresa una cantidad válida.");
          }
        });
      });
    });

  </script>

  <script src="./assets/js/jquery.min.js.descarga"></script>
  <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  <script src="./assets/js/bootstrap.min.js.descarga"></script>
  <script src="./assets/js/holder.min.js.descarga"></script>
  <script src="./assets/js/ie10-viewport-bug-workaround.js.descarga"></script>

</body>

</html>