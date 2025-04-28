import cv2
from pyzbar.pyzbar import decode
import time

# Initialize the camera
cam = cv2.VideoCapture(0)
cam.set(3, 640)  # Width
cam.set(4, 480)  # Height

while True:
    success, frame = cam.read()
    if not success:
        break
    
    # Decode the QR codes in the frame
    for barcode in decode(frame):
        print(barcode.type)
        print(barcode.data.decode('utf-8'))
        time.sleep(1)  # Wait for 1 second before continuing

        cam.release()
        cv2.destroyAllWindows()
        exit()
                
    # Display the frame
    cv2.imshow("QR_Code_Scanner", frame)
    
    # Check for 'q' key press to exit
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Release the camera and close all OpenCV windows
cam.release()
cv2.destroyAllWindows()
