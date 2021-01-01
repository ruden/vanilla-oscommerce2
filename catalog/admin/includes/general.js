function rowOverEffect(object) {
  if (object.className === 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className === 'dataTableRowOver') object.className = 'dataTableRow';
}

function toggleDivBlock(id) {
  if (document.getElementById) {
    itm = document.getElementById(id);
  } else if (document.all){
    itm = document.all[id];
  } else if (document.layers){
    itm = document.layers[id];
  }

  if (itm) {
    if (itm.style.display !== "none") {
      itm.style.display = "none";
    } else {
      itm.style.display = "block";
    }
  }
}
