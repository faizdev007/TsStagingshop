// document.addEventListener('DOMContentLoaded', function() {
//     const sections = {
//         type: document.getElementById('type-section'),
//         location: document.getElementById('location-section'),
//         area: document.getElementById('area-section'),
//         complex: document.getElementById('complex-section')
//     };

//     const inputs = {
//         propertyType: document.getElementById('property_type_list'),
//         location: document.getElementById('location_list'),
//         area: document.getElementById('area_list'),
//         complex: document.getElementById('complex_list')
//     };

//     const breadcrumb = document.getElementById('breadcrumb');

//     function loadDynamicSearchData(type, parentValue = '', parentType = '') {
//         const buttonContainer = document.getElementById(`${type}-buttons`);
//         buttonContainer.innerHTML = `
//             <div class="loading">
//                 <span>Loading ${type} data...</span>
//             </div>
//         `;

//         const params = new URLSearchParams({
//             type: type,
//             parent: parentValue || '',
//             parentType: parentType || ''
//         });

//         fetch(`/get-dynamic-search-data?${params}`)
//             .then(response => {
//                 if (!response.ok) {
//                     throw new Error(`HTTP error! status: ${response.status}`);
//                 }
//                 return response.json();
//             })
//             .then(data => {
//                 if (!Array.isArray(data) || data.length === 0) {
//                     throw new Error('No data received');
//                 }

//                 buttonContainer.innerHTML = '';

//                 data.forEach((item, index) => {
//                     const button = document.createElement('button');
//                     button.type = 'button';
//                     button.className = `btn btn-${type}`;
//                     button.dataset.value = item.id;
                    
//                     button.innerHTML = `${item.name}<span class="listing-count" id="count-${item.id}">(${item.count || 0})</span>`;
                    
//                     button.addEventListener('click', function() {
//                         handleSearchSelection(type, item);
//                     });

//                     if (index >= 5) {
//                         button.classList.add('more');
//                         button.style.display = 'none';
//                     }

//                     buttonContainer.appendChild(button);
//                 });

//                 updateMoreButton(type);
//             })
//             .catch(error => {
//                 console.error('Error loading search data:', error);
                
//                 buttonContainer.innerHTML = `
//                     <div class="error">
//                         <strong>Failed to load ${type} data</strong>
//                         <p>Please try again or contact support.</p>
//                         <small>Error: ${error.message}</small>
//                     </div>
//                 `;

//                 logErrorToServer(error, type, parentValue, parentType);
//             });
//     }

//     function handleSearchSelection(type, item) {
//         switch(type) {
//             case 'property_type':
//                 inputs.propertyType.value = item.id;
//                 loadDynamicSearchData('location', item.id, 'property_type');
//                 hideShowSections('type', 'location');
//                 updateBreadcrumb('Property Type', item.name);
//                 break;
            
//             case 'location':
//                 inputs.location.value = item.id;
//                 loadDynamicSearchData('area', item.id, 'location');
//                 hideShowSections('location', 'area');
//                 updateBreadcrumb('Location', item.name);
//                 break;
            
//             case 'area':
//                 inputs.area.value = item.id;
//                 loadDynamicSearchData('complex', item.id, 'area');
//                 hideShowSections('area', 'complex');
//                 updateBreadcrumb('Area', item.name);
//                 break;
            
//             case 'complex':
//                 inputs.complex.value = item.id;
//                 hideShowSections('complex', 'type');
//                 updateBreadcrumb('Complex', item.name);
//                 break;
//         }
//     }

//     function hideShowSections(hide, show) {
//         sections[hide].hidden = true;
//         sections[show].hidden = false;
//     }

//     function updateBreadcrumb(type, value) {
//         const breadcrumbItem = document.createElement('li');
//         breadcrumbItem.textContent = value;
//         breadcrumb.appendChild(breadcrumbItem);
//     }

//     function updateMoreButton(type) {
//         const moreButton = document.querySelector(`#${type}-section .dropdown-more`);
//         const hiddenOptions = document.querySelectorAll(`#${type}-buttons .more`);
        
//         moreButton.onclick = function() {
//             if (moreButton.textContent === 'More') {
//                 hiddenOptions.forEach(option => option.style.display = 'block');
//                 moreButton.textContent = 'Less';
//             } else {
//                 hiddenOptions.forEach(option => option.style.display = 'none');
//                 moreButton.textContent = 'More';
//             }
//         };
//     }

//     function logErrorToServer(error, type, parentValue, parentType) {
//         fetch('/log-frontend-error', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             },
//             body: JSON.stringify({
//                 error: error.toString(),
//                 type: type,
//                 parentValue: parentValue,
//                 parentType: parentType,
//                 url: window.location.href
//             })
//         });
//     }

//     // Initial setup - attach event listeners to property type buttons
//     document.querySelectorAll('.btn-property_type').forEach(button => {
//         button.addEventListener('click', function() {
//             handleSearchSelection('property_type', {
//                 id: this.dataset.value,
//                 name: this.textContent.split('(')[0].trim()
//             });
//         });
//     });
// });

// // Existing toggle more function
// function toggleMore(section) {
//     const sectionElement = document.getElementById(`${section}-buttons`);
//     const moreButton = document.querySelector(`#${section}-section .dropdown-more`);
//     const hiddenOptions = sectionElement.querySelectorAll('.more');
    
//     if (moreButton.textContent === 'More') {
//         hiddenOptions.forEach(option => option.style.display = 'block');
//         moreButton.textContent = 'Less';
//     } else {
//         hiddenOptions.forEach(option => option.style.display = 'none');
//         moreButton.textContent = 'More';
//     }
// }