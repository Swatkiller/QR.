import cv2
from pyzbar.pyzbar import decode
import subprocess
import time
import base64
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives import padding

# Hardcoded AES key and IV
key = b'\x97\xc9I\xd3\xfe\xbe2\x00\x97-TU\x8e\xfd/2'  # 16 bytes for AES-128
iv = b'\x9eCyW\xd2\xb0\x9eO)\x0e\xa5\xf5\xdc\xf4\xde"'  # 16 bytes for IV

def decrypt_data(encrypted_data):
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

def simple_qr_scanner():
    # Initialize the camera
    cam = cv2.VideoCapture(0)
    cam.set(3, 640)  # Width
    cam.set(4, 480)  # Height

    scanned_sids = set()  
    qr_scanned = False  

    while True:
        success, frame = cam.read()
        if not success:
            print("Error: Failed to grab frame.")
            break
        
        # Decode the QR codes in the frame
        barcodes = decode(frame)
        if barcodes:
            for barcode in barcodes:
                encrypted_data = barcode.data.decode('utf-8')
                print(f"Type: {barcode.type}")
                print(f"Data: {encrypted_data}")

                # Extract sid from the decrypted data
                sid = extract_sid_from_data(encrypted_data)
                
                if sid not in scanned_sids:
                    # Call decrypter.py with the encrypted data
                    subprocess.run(['python', 'decrypter.py', encrypted_data])
                    scanned_sids.add(sid)  # Add sid to the set
                    print(f"Scanned and processed sid: {sid}")

                    (x, y, w, h) = barcode.rect
                    cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
                    time.sleep(1)  

                    qr_scanned = True 
                    break  # Exit the for loop

        # Display the frame
        cv2.imshow("QR_Code_Scanner", frame)
        
        # Check for 'q' key press to exit or if a QR code has been scanned
        if cv2.waitKey(1) & 0xFF == ord('q') or qr_scanned:
            break

    # Release the camera and close all OpenCV windows
    cam.release()
    cv2.destroyAllWindows()

def extract_sid_from_data(encrypted_data):
    decrypted_data = decrypt_data(encrypted_data) 
    lines = decrypted_data.split("\n")
    sid = lines[0].split(": ")[1]  
    return sid

if __name__ == "__main__":
    simple_qr_scanner()
