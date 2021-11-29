window.onload = async () => {
    await loadUsers();
};

let path=window.location.href;



const loadUsers = async () => {
    document.getElementById("titlec").innerText = `CONTACTOS`;

    let htmli = `<tr>
                    <td scope="row" colspan="6" style="text-align:center;">SELECCIONE UN USUARIO</td>
                 </tr>`;
    document.getElementById("tbodyC").innerHTML = htmli;


    const options = {
        URL: path+'usuarios',
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        }
    }
    let response = await fetch(options.URL, options);
    let result = await response.json();
    let html;
    if (result.length > 0) {
        html = result
            .map(
                (e, i) => `
                    <tr>
                        <td scope="row" style="text-align:center;">${e.identificacion}</td>
                        <td scope="row" style="text-align:center;">${e.nombres}</td>
                        <td style="text-align:center;">${e.apellidos}</td>
                        <td style="text-align:center;">${e.fecha_nacimiento}</td>
                        <td style="text-align:center;">${e.genero}</td>
                        <td style="text-align:center;"><a class="btd" onclick="showContacts(this)" data-id=${e.id_usuarios} data-name=${e.nombres} title="Ver contactos"><i class="fas fa-address-book"></a></i></td>
                        <td style="text-align:center;"><a class="btd" onclick="editUser(this)" data-edit-user="${e.identificacion},${e.nombres},${e.apellidos},${e.fecha_nacimiento},${e.genero},${e.id_usuarios}"  title="Editar"><i class="fas fa-edit"></i></a></td>
                        <td style="text-align:center;"><a class="btd" onclick="deleteUorC(this)" data-id=${e.id_usuarios} data-name=${e.nombres} title="Eliminar"><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
            `)
            .join(" ");
    } else {
        html = `
                    <tr>
                        <td scope="row" colspan="8" style="text-align:center;">HO HAY USUARIOS</td>
                    </tr>`;
    }
    document.getElementById("tbodyU").innerHTML = html;

}

var myModal = new bootstrap.Modal(document.getElementById('addCModal'));
let addC = document.getElementById("addContact");
addC.addEventListener("click", (event) => {
    myModal.show()
});

const showContacts = async (user) => {
    const id = user.getAttribute("data-id");
    document.getElementById('ihac').value=id
    const name = user.getAttribute("data-name").toUpperCase();
    document.getElementById("addCModalLabel").innerText = `AGREGAR CONTACTO A ${name}`;

    document.getElementById("addContact").innerHTML = `<i class="fas fa-plus-circle pt-2 fs-1 addicon" >`
    document.getElementById("titlec").innerText = `CONTACTOS DE ${name}`
    const options = {
        URL: `${path}usuarios?id=${id}`,
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    }

    let response = await fetch(options.URL, options);
    let result = await response.json();
    let html;
    if (result.length > 0) {
        html = result
            .map(
                (e, i) => `
            <tr>
                <td scope="row" style="text-align:center;">${e.nombre}</td>
                <td style="text-align:center;">${e.numero}</td>
                <td style="text-align:center;">${e.tipo_numero}</td>
                <td style="text-align:center;">${e.parentesco}</td>
                <td style="text-align:center;"><a class="btd" onclick="editContact(this)" data-edit-contact="${e.id_contactos},${e.nombre},${e.numero},${e.tipo_numero},${e.parentesco},${e.fk_usuarios}" title="Editar"><i class="fas fa-edit"></i></a></td>
                <td style="text-align:center;"><a class="btd" onclick="deleteUorC(this)" data-id-deleteC=${e.id_contactos} data-opt="contacto" data-name=${e.nombre} title="Eliminar"><i class="fas fa-trash-alt"></i></a></td>
            </tr>
            `)
            .join(" ");
    } else {
        html = `
                    <tr>
                        <td scope="row" colspan="6" style="text-align:center;">HO HAY CONTACTOS</td>
                    </tr>`;
    }
    document.getElementById("tbodyC").innerHTML = html;
}


//Agregar contacto
let addContactF = document.getElementById("addContactF");
addContactF.addEventListener("submit", async (event) => {
    let currentUser= document.getElementById('ihac').value;
    event.preventDefault();
    let valid = true;
    let formdata = {
        names: document.getElementById('namesc').value,
        tel: document.getElementById('telnumber').value,
        telType: document.getElementById('teltype').value,
        kin: document.getElementById('kin').value,
        fk: currentUser
    };

    for (const property in formdata) {
        if (formdata[property] == "") {
            console.log(`${property}: ${formdata[property]}`)
            valid = false;
        };
    }

    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Debe diligenciar el formulario completo"
        })
        return;
    }

    const options = {
        URL: path+'contactos',
        body: JSON.stringify(formdata),
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        }
    }
    try {
        await fetch(options.URL, options);
        setTimeout(() => {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `${formdata.names} agregado`,
                showConfirmButton: false,
                timer: 1500
            })
            document.querySelector(`[data-id='${currentUser}']`).click();
            myModal.hide();
            addContactF.reset()

        }, 200);
    } catch (error) {
        console.log(error)
    }
})



//Agregar usuario
let addUForm = document.getElementById("addUserF");
addUForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    let valid = true;
    const formdata = {
        identification: document.getElementById('ident').value,
        names: document.getElementById('names').value,
        lastName: document.getElementById('lastname').value,
        birthDate: document.getElementById('birthdate').value,
        gender: document.getElementById('gender').value,
    };

    for (const property in formdata) {
        if (formdata[property] == "") {
            console.log(`${property}: ${formdata[property]}`)
            valid = false;
        };
    }

    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Debe diligenciar el formulario completo"
        })
        return;
    }

    const options = {
        URL: path+'usuarios',
        body: JSON.stringify(formdata),
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        }
    }
    let myModal1 = bootstrap.Modal.getInstance(document.getElementById('addUModal'));
    try {
        await fetch(options.URL, options);
        setTimeout(async () => {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `${formdata.names} agregado`,
                showConfirmButton: false,
                timer: 1500
            })
            myModal1.hide()
            await loadUsers();
            addUForm.reset();
        }, 200);
    } catch (error) {
        console.log(error)
    }
})


//EDITAR
var myModaleu = new bootstrap.Modal(document.getElementById('editUModal'))

const editUser = async (object) => {
    myModaleu.show()
    let string = object.getAttribute("data-edit-user");
    const user = string.split(",");
    document.getElementById('iheu').value=user[5]
    document.getElementById("editUModalLabel").innerText = `EDITAR A ${user[1].toUpperCase()}`;

    document.getElementById('idente').value = user[0];
    document.getElementById('namese').value = user[1];
    document.getElementById('lastnamee').value = user[2];
    document.getElementById('birthdatee').value = user[3];
    document.getElementById('gendere').value = user[4];
}

let editUserF = document.getElementById("editUserF");
editUserF.addEventListener("submit", async (event) => {
    let currentUser= document.getElementById('iheu').value;
    event.preventDefault();
    let valid = true;
    const formdata = {
        userId: currentUser,
        identification: document.getElementById('idente').value,
        names: document.getElementById('namese').value,
        lastName: document.getElementById('lastnamee').value,
        birthDate: document.getElementById('birthdatee').value,
        gender: document.getElementById('gendere').value,
    };
    for (const property in formdata) {

        if (formdata[property] == "") {
            console.log(`${property}: ${formdata[property]}`)
            valid = false;
        };
    }

    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Debe diligenciar el formulario completo"
        })
        return;
    }

    const options = {
        URL: path+'usuarios',
        body: JSON.stringify(formdata),
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        }
    }
    try {
        await fetch(options.URL, options);
        setTimeout(async () => {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `${formdata.names} se ha actualizado`,
                showConfirmButton: false,
                timer: 1500
            })
            editUserF.reset();
            myModaleu.hide();
            await loadUsers();
        }, 500);
    } catch (error) {
        console.log(error)
    }
})

var myModalec = new bootstrap.Modal(document.getElementById('editCModal'))
const editContact = async (object) => {
    let string = object.getAttribute("data-edit-contact");
    const contact = string.split(",");
    document.getElementById("editCModalLabel").innerText = `EDITAR A ${contact[1].toUpperCase()}`;
    document.getElementById('ihec').value=contact[0];


    document.getElementById('namesce').value = contact[1];
    document.getElementById('telnumbere').value = parseInt(contact[2]);
    document.getElementById('teltypee').value = contact[3];
    document.getElementById('kine').value = contact[4];


    myModalec.show()
    //Agregar contacto
}

let editContactF = document.getElementById("editContactF");
editContactF.addEventListener("submit", async (event) => {

    event.preventDefault();
    let valid = true;
    let currentContact= document.getElementById('ihec').value;
    let currentUser=document.getElementById('ihac').value;
    const formdata = {
        contactId: currentContact,
        names: document.getElementById('namesce').value,
        tel: document.getElementById('telnumbere').value,
        telType: document.getElementById('teltypee').value,
        kin: document.getElementById('kine').value,
    };
    for (const property in formdata) {

        if (formdata[property] == "") {
            console.log(`${property}: ${formdata[property]}`)
            valid = false;
        };
    }

    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Debe diligenciar el formulario completo"
        })
        return;
    }

    const options = {
        URL: path+'contactos',
        body: JSON.stringify(formdata),
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        }
    }
    try {
        await fetch(options.URL, options);
        setTimeout(async () => {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `${formdata.names} se ha actualizado`,
                showConfirmButton: false,
                timer: 1500
            })
            editContactF.reset();
            await document.querySelector(`[data-id='${currentUser}']`).click();
            myModalec.hide();
        }, 200);
    } catch (error) {
        console.log(error)
    }
})


const deleteUorC = async (data) => {
    const id = data.getAttribute("data-opt") ?data.getAttribute("data-id-deleteC"): data.getAttribute("data-id");
    const name = data.getAttribute("data-name");
    let currentUser=document.getElementById('ihac').value;
    let endurl = data.getAttribute("data-opt") ? "contactos" : "usuarios";
    let headersC = data.getAttribute("data-opt") ? {
        "Content-Type": "application/json",
        "contactId": id
    } : {
        "Content-Type": "application/json",
        "userId": id
    };

    const options1 = {
        URL: path+'usuarios',
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        }
    }

    let response = await fetch(options1.URL, options1);
    let isOne =await response.json();
    

    const options = {
        URL: `${path}${endurl}`,
        method: "DELETE",
        headers: headersC
    }


    await fetch(options.URL, options);
    setTimeout(async () => {
        await Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: `${name} se ha eliminado`,
            showConfirmButton: false,
            timer: 1500
        })
        await loadUsers();
        isOne.length==1?location.reload():document.querySelector(`[data-id='${currentUser}']`).click();
    }, 200);
}