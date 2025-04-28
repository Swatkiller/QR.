import sys
import qrcode
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend
import os
import base64
from cryptography.hazmat.primitives import padding

if len(sys.argv) != 4:
    print("Usage: generate_qr.py <id> <grade> <section>")
    sys.exit(1)

student_id = sys.argv[1]
grade = sys.argv[2]
section = sys.argv[3]

# Create the QR code data, using 'sid' for student_id
qr_data = f"sid: {student_id}\nGrade: {grade}\nSection: {section}"

# Hardcoded AES key and IV
key = b'\x97\xc9I\xd3\xfe\xbe2\x00\x97-TU\x8e\xfd/2'  # 16 bytes for AES-128
iv = b'\x9eCyW\xd2\xb0\x9eO)\x0e\xa5\xf5\xdc\xf4\xde"'  # 16 bytes for IV

# Function to encrypt data using AES
def encrypt_data(data, key, iv):
    if isinstance(data, str):
        data = data.encode('utf-8')

    cipher = Cipher(algorithms.AES(key), modes.CBC(iv), backend=default_backend())
    encryptor = cipher.encryptor()

    # Apply PKCS7 padding
    padder = padding.PKCS7(algorithms.AES.block_size).padder()
    padded_data = padder.update(data) + padder.finalize()

    # Encrypt the data
    encrypted_data = encryptor.update(padded_data) + encryptor.finalize()

    # Return the encrypted data, encoded as base64
    return base64.b64encode(encrypted_data).decode('utf-8')

# Encrypt the QR data
encrypted_qr_data = encrypt_data(qr_data, key, iv)

# Generate the QR code with encrypted data
qr = qrcode.QRCode(
    version=1,
    error_correction=qrcode.constants.ERROR_CORRECT_L,
    box_size=10,
    border=4,
)
qr.add_data(encrypted_qr_data)
qr.make(fit=True)

# Define the output directory
output_dir = "./qrcodes/"
os.makedirs(output_dir, exist_ok=True)

# Save the QR code as an image file
qr_code_path = os.path.join(output_dir, f"student_{student_id}.png")
img = qr.make_image(fill='black', back_color='white')
img.save(qr_code_path)

print(f"Encrypted QR code generated and saved at {qr_code_path}")
