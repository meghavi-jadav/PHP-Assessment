async function fetchCountries() {
    const response = await fetch('api.php');
    const countries = await response.json();
    const tbody = document.getElementById('countryTable').querySelector('tbody');
    tbody.innerHTML = '';

    countries.forEach(country => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${country.country_name}</td>
            <td><img src="${country.flag_image}" alt="Flag" width="50"></td>
            <td>${country.capital}</td>
            <td>${country.region}</td>
            <td>${country.country_code}</td>
            <td>${country.created_at}</td>
            <td>${country.updated_at}</td>
            <td>
                <button onclick="editCountry(${country.id})">Edit</button>
                <button onclick="deleteCountry(${country.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

async function addCountry(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('addCountryForm'));
    const response = await fetch('api.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.text();
    alert(result);
    fetchCountries();
}

async function editCountry(id) {
    const response = await fetch(`api.php?id=${id}`);
    const [country] = await response.json(); // Expecting the response to be an array with a single object

    document.getElementById('edit_id').value = country.id;
    document.getElementById('edit_country_name').value = country.country_name;
    document.getElementById('edit_flag_image').value = country.flag_image;
    document.getElementById('edit_capital').value = country.capital;
    document.getElementById('edit_region').value = country.region;
    document.getElementById('edit_country_code').value = country.country_code;

    document.getElementById('editCountryForm').style.display = 'block';
}

async function updateCountry(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('editCountryForm'));
    const response = await fetch('api.php', {
        method: 'PUT',
        body: new URLSearchParams(formData)
    });
    const result = await response.text();
    alert(result);
    document.getElementById('editCountryForm').style.display = 'none';
    fetchCountries();
}

async function deleteCountry(id) {
    const response = await fetch('api.php', {
        method: 'DELETE',
        body: new URLSearchParams({ id })
    });
    const result = await response.text();
    alert(result);
    fetchCountries();
}

document.getElementById('addCountryForm').addEventListener('submit', addCountry);
document.getElementById('editCountryForm').addEventListener('submit', updateCountry);

fetchCountries();