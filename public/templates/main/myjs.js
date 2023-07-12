
// let allpagenumber = sessionStorage.getItem('allpagenumber');
// if(allpagenumber == null){
// 	sessionStorage.setItem('allpagenumber', 1);
// 	let allpagenumber = sessionStorage.getItem('allpagenumber');
// }
// function changepagenum(pagenum){
// 	sessionStorage.setItem('allpagenumber', pagenum);
// }
// console.log(allpagenumber);
var pagenumcurrent = 1;
var lateststraridavailable = "none";




function loaderon(){
	$("#loader,#cover").fadeIn("fast");
}
function loaderoff(){
	$("#loader,#cover").fadeOut("fast");
}
function loadMyURL(myurl,what=""){
	if(what == "blank"){
		window.open(myurl, '_blank');
	}else{
		window.location.href = myurl;
	}
}
function goBack() {
  window.history.back();
}

function change_select_val(id,val){
  if(val == "" || val == 0){
    $('#'+id).prop("selectedIndex", 0);
  }else{
    $('#'+id).val(val).change();
  }
}

function save_add_reset(fieldnum,htmltbname,htmlpage){
	$("#A_save").on("click",function(){
		loaderon();
	    let data = [];
	    for (var i = 0; i < fieldnum; i++) {
	      data.push($("#A"+i).val());
	    }
	    $("#err").load("../router.php",{
	      save_data: data,
	      save_tbnm: htmltbname,
	      save_page: htmlpage
	    });
	});

	$("#A_reset").on("click",function(){
		loadMyURL(htmlpage);
	});
}


function getserchval(htmltbname,searchdata,sort,page,htmlpage){
	loaderon();
	$("#load_table_here").load("../router.php",{
	      load_table_htmltbname: htmltbname,
	      load_table_searchdata: searchdata,
	      load_table_sort: sort,
	      load_table_page: page,
	      load_table_htmlpage: htmlpage
	});
}


function findbutton(searchformnum,htmltbname,htmlpage){
	$("#B_find").on("click",function(){
		loaderon();
		let data = "";
		let sort = $("#B_sort").val();
	    for (var i = 0; i < searchformnum; i++) {
	      data += $("#B"+i).val()+",";
	    }
	    data.substring(0,data.length -1);
		getserchval(htmltbname,data,sort,1,htmlpage);
	});
}

////////////////////////////////////////////////////////////////////////
function reset_dis(ids){
	$(ids).val("");
}
function resetS(){
	$("#S_email,#S_lname,#S_fname,#S_pass,#S_repass").val("");
}
function resetF(){
	$("#F_email,#F_lname,#F_fname").val("");
}
function resetL(){
	$("#L_email,#L_pass").val("");
}


function saveprofiledata(what,data){
	loaderon();
	$("#err").load("router.php",{
		save_profile_what: what,
		save_profile_data: data
    });
}


function savelistingdata(data){
	loaderon();
	$("#err").load("router.php",{
		save_listing_data: data
    });
}

function errmsg(alerttype,strong,msg){
	let out = "<div class='alert alert-"+alerttype+" alert-dismissible fade show' role='alert'>";
	out += "<strong>"+strong+"</strong> "+msg;
	out += "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
	out += "</div>";
	return out;
}


function load_items_in_select(val,id_out){
	if(val != ""){
		$("#"+id_out).load("router.php",{
			load_items_in_select: val
	    });
	}
}


function check_value_and_disable(id_a,id_b){
	let a = $("#"+id_a).val();
	if(a == ""){
		$("#"+id_b).prop("disabled",true);
		$("#"+id_b).val("");
	}else{
		$("#"+id_b).prop("disabled",false);
	}
	load_items_in_select(a,id_b);
}

function check_value_and_disable_onchange(id_a,id_b){
	$("#"+id_a).on("change",function(){
		let a = $(this).val();
		if(a == ""){
			$("#"+id_b).prop("disabled",true);
			$("#"+id_b).val("");
		}else{
			$("#"+id_b).prop("disabled",false);
		}
		load_items_in_select(a,id_b);
	});
}

function filterSymbols(distext,reverse="no"){
	if(reverse == "no"){
		let lv1 = distext.replace(/(?:\\[rn]|[\r\n])/g, "<br>");
		let lv2 = lv1.replace(/'/g, "&#39;");
		return lv2;
	}else{
		let lv1 = distext.replace(/<br>/g,"\r\n");
		let lv2 = lv1;
		return lv2;
	}
	
}


function viewdetails(id){
	loadMyURL("details.php?id="+id,"blank");
}
function viewlistingdetails(usercode,listingkeys){
	loadMyURL("ads.php?ucode="+usercode+"&lkey="+listingkeys,"blank");
}

function changestatusofdisemail(email){
	loaderon();
	let what = $(".statval").val();
	$("#err").load("router.php",{
		change_status_of_dis_email_email: email,
		change_status_of_dis_email_what: what
    });
}


function resendverification(){
	$("#err").load("router.php",{
		trigger_email_ver: 1
    });
}




function sortTable_one(n,htmltbn,isdate=0) {
  let table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(htmltbn);
  switching = true;
  dir = "asc"; 
  while (switching) {
    switching = false;
    rows = table.rows;
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      let xx = parseInt(x.innerHTML);
      let yy = parseInt(y.innerHTML);
      if (dir == "asc") {
        if(isdate == 1){
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            shouldSwitch = true;
            break;
          }
        }else{
          if($.isNumeric(xx) && $.isNumeric(yy)){
            if (xx > yy) {
              shouldSwitch = true;
              break;
            }
          }else{
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          }
        }
      } else if (dir == "desc") {
        if(isdate == 1){
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            shouldSwitch = true;
            break;
          }
        }else{
          if($.isNumeric(xx) && $.isNumeric(yy)){
            if (xx < yy) {
              shouldSwitch = true;
              break;
            }
          }else{
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          }
        }
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount ++; 
    } else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}


function expirationcheck(){
	loaderon();
	$("#err").load("../router.php",{
		expirationcheck: 1
    });
}

function cretedummy(){
	loaderon();
	$("#err").load("../router.php",{
		cretedummy: 1
    });
}

function copydis(disval){
	var input = document.createElement("input");
	input.setAttribute('type', 'text');
	input.setAttribute('value', disval);
	input.setAttribute('id', 'temp');
	document.body.appendChild(input);
	var copyText = document.getElementById('temp');
	copyText.select(); 
		copyText.setSelectionRange(0, 99999);
		document.execCommand("copy");
		input.remove();
}


function loaditemdel(id,imgpath,title,price){
	let out = "<div class='modal-body'>";
	out += "<div>";
	out += "<ul class='list-group list-group-flush'>";
	out += "<li class='list-group-item'>";
	out += "<img src='"+imgpath+"' class='img-thumbnail' alt='img-thumbnail'>";
	out += "</li>";
	out += "<li class='list-group-item'>Listing: "+title+"</li>";
	out += "<li class='list-group-item'>Price: "+price+"</li>";
	out += "</ul>";
	out += "<br>";
	out += "</div>";
	out += "<div class='alert alert-danger' role='alert'>";
	out += "Are you sure you want to delete this item?";
	out += "</div>";
	out += "</div>";
	out += "<div class='modal-footer'>";
	out += "<button onclick='del_ads("+id+")' type='button' class='btn btn-info bg-col1 text-white border-0' data-bs-dismiss='modal'>Delete</button>";
	out += "</div>";

	$("#load_item_del").html(out);
}

function del_ads(id){
	$("#err").load("router.php",{
		del_ads: id
	});
}
