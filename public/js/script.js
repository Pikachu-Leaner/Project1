// Function for delete confirmation
function confirmDelete(productId, productName) {
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm '" + productName + "' không?")) {
        // Construct the delete URL based on your MVC pattern
        const deleteUrl = '/Product/delete/' + productId;
        window.location.href = deleteUrl;
    }
}