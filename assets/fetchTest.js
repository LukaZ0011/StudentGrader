function fetchData() {
  console.log('Fetching data...');
  const formData = new FormData();
  formData.append('projekt', 'p_lzivoder');
  formData.append('procedura', 'p_login');
  formData.append('username', 'tcoats2r');
  formData.append('password', 'rD7*M@+9#~$i');
  formData.append('imeStudenta', 'Ivan');

  // Use the proxy server URL
  fetch('http://localhost:3000/proxy', { // Proxy endpoint
      method: 'POST',
      body: formData,
  })
  .then(response => {
      if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.text(); // or .json() if JSON is returned
  })
  .then(data => console.log('Response:', data))
  .catch(error => console.error('Error:', error));
}