function selectDbType(){
    var dbtype = document.getElementById("DbType").value;
    var displayPortField = 'none';
    var inputIsRequired = false;
    if(dbtype == 'pgsql'){
        displayPortField = 'block';
        inputIsRequired = true;
    }

    var portlabelClassList = document.getElementsByClassName("portlabel");
    var portinputClassList = document.getElementsByClassName("portinput");
    var portDisplay = portinputClassList[0].style.display;
    if(portDisplay != displayPortField ){
        for(i=0;i <= portinputClassList.length-1; i++){
            portinputClassList[i].style.display = displayPortField;
            portinputClassList[i].required = inputIsRequired;
            portlabelClassList[i].style.display = displayPortField;
        }
    }
}

