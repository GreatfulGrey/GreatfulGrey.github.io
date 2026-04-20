<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PharmaCool ERP — Batch & Vendor Lookup</title>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/batch_lookup.css">
</head>
<body>

<nav>
  <div class="nav-logo">Pharma<span>Cool</span> ERP</div>
  <div class="nav-breadcrumb">
    <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?> &rsaquo;
    <b>Batch &amp; Vendor Lookup</b>
  </div>
</nav>

<div class="page">
  <h1>Batch &amp; Vendor Lookup</h1>
  <p class="subtitle">Search by vendor name, batch number, or expiry date range.</p>

  <!-- Search bar + date range -->
  <div class="search-row">
    <div class="search-wrap">
      <input id="searchInput" type="text"
             placeholder="Search vendor name or batch number…"
             autocomplete="off">
    </div>
    <div class="date-range">
      <label>Expiry from</label>
      <input type="date" id="dateFrom">
      <label>to</label>
      <input type="date" id="dateTo">
      <button id="clearDates">Clear</button>
    </div>
  </div>

  <div class="results-meta" id="resultsMeta"></div>

  <div class="table-wrap" id="tableWrap">
    <table id="batchTable">
      <thead>
        <tr>
          <th data-col="vendor">Vendor Name <span class="sort-arrow">↕</span></th>
          <th data-col="vendorid">Vendor ID <span class="sort-arrow">↕</span></th>
          <th data-col="batch">Batch # <span class="sort-arrow">↕</span></th>
          <th data-col="mfg">Manufacture Date <span class="sort-arrow">↕</span></th>
          <th data-col="expiry">Expiry Date <span class="sort-arrow">↕</span></th>
          <th data-col="volume">Total Volume <span class="sort-arrow">↕</span></th>
          <th data-col="temp">Temp Range <span class="sort-arrow">↕</span></th>
        </tr>
      </thead>
      <tbody id="tableBody"></tbody>
    </table>
    <div class="empty" id="emptyState">No batches match your search.</div>
  </div>
</div>

<!-- LOTS MODAL — shows lots and locations for a batch -->
<div class="modal-overlay" id="lotsModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">
        <h2 id="modalTitle">Batch Details</h2>
        <span class="modal-sub" id="modalSub"></span>
      </div>
      <button class="close-btn" id="closeLotsModal">&times;</button>
    </div>
    <div class="modal-meta" id="modalMeta"></div>
    <div class="modal-body">
      <div class="modal-section-title">Lots &amp; Current Locations</div>
      <div class="lot-table-wrap">
        <table class="lot-table">
          <thead>
            <tr>
              <th>Lot #</th>
              <th>Volume</th>
              <th>Created</th>
              <th>Current Location</th>
            </tr>
          </thead>
          <tbody id="lotsTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- VENDOR MODAL — shows vendor info + all their batches -->
<div class="modal-overlay" id="vendorModal">
  <div class="modal modal-wide">
    <div class="modal-header">
      <div class="modal-title">
        <h2 id="vendorModalTitle">Vendor Details</h2>
        <span class="modal-sub" id="vendorModalSub"></span>
      </div>
      <button class="close-btn" id="closeVendorModal">&times;</button>
    </div>

    <!-- Vendor contact info grid -->
    <div class="modal-meta" id="vendorModalMeta"></div>

    <!-- All batches from this vendor -->
    <div class="modal-body">
      <div class="modal-section-title">All Batches from this Vendor</div>
      <div class="lot-table-wrap">
        <table class="lot-table">
          <thead>
            <tr>
              <th>Batch #</th>
              <th>Manufacture Date</th>
              <th>Expiry Date</th>
              <th>Total Volume</th>
              <th>Temp Range</th>
            </tr>
          </thead>
          <tbody id="vendorBatchesBody"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="js/batch_lookup.js"></script>
</body>
</html>