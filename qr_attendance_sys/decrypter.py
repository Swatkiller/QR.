import sys
import base64
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives import padding
import requests

# Hardcoded AES key and IV
key = b'\x97\xc9I\xd3\xfe\xbe2\x00\x97-TU\x8e\xfd/2'  # 16 bytes for AES-128
iv = b'\x9eCyW\xd2\xb0\x9eO)\x0e\xa5\xf5\xdc\xf4\xde"'  # 16 bytes for IV

# Function to decrypt data using AES
def decrypt_data(encrypted_data, key, iv):
    # Decode the base64-encoded data
    encrypted_data_bytes = base64.b64decode(encrypted_data)

    # Initialize the AES cipher in CBC mode
    cipher = Cipher(algorithms.AES(key), modes.CBC(iv), backend=default_backend())
    decryptor = cipher.decryptor()

    # Decrypt the data
    padded_data = decryptor.update(encrypted_data_bytes) + decryptor.finalize()

    # Remove padding
    unpadder = padding.PKCS7(algorithms.AES.block_size).unpadder()
    data = unpadder.update(padded_data) + unpadder.finalize()

    return data.decode('utf-8')

if __name__ == "__main__":
    
    encrypted_data = sys.argv[1]  # Get the encrypted data from command line arguments

    # Decrypt the data
    decrypted_data = decrypt_data(encrypted_data, key, iv)

    # Print the decrypted data
    print("Decrypted data:", decrypted_data)

    # Extracting ID, grade, and section
    lines = decrypted_data.split("\n")
    student_id = lines[0].split(": ")[1]
    grade = lines[1].split(": ")[1]
    section = lines[2].split(": ")[1]

    # Prepare the data for the POST request
    data = {
        'sid': student_id,
        'grade': grade,
        'section': section
    }

    # Send the data to mark_attendance.php
    response = requests.post("http://localhost/project-II/qr_attendance_sys/mark_attendance.php", data=data)

    # Print the response from the server
    print("Response from mark_attendance.php:", response.text)
