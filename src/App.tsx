import React, { useState } from 'react';
import styled from 'styled-components';
import Webcam from 'react-webcam';
import Draggable from 'react-draggable';
import { motion } from 'framer-motion';

const Container = styled.div`
  width: 100vw;
  height: 100vh;
  background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
  overflow: hidden;
  position: relative;
`;

const FloatingFace = styled(motion.div)`
  position: absolute;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background-size: cover;
  background-position: center;
  cursor: grab;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  &:active {
    cursor: grabbing;
  }
`;

const CaptureButton = styled.button`
  position: fixed;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 12px 24px;
  background: rgba(255, 255, 255, 0.8);
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-size: 16px;
  transition: all 0.3s ease;
  &:hover {
    background: rgba(255, 255, 255, 1);
    transform: translateX(-50%) scale(1.05);
  }
`;

const WebcamContainer = styled.div<{ isVisible: boolean }>`
  position: fixed;
  bottom: 80px;
  left: 50%;
  transform: translateX(-50%);
  display: ${props => props.isVisible ? 'block' : 'none'};
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
`;

interface Face {
  id: number;
  imageUrl: string;
  x: number;
  y: number;
}

const App: React.FC = () => {
  const [faces, setFaces] = useState<Face[]>([]);
  const [showWebcam, setShowWebcam] = useState(false);
  const webcamRef = React.useRef<Webcam>(null);

  const captureImage = React.useCallback(() => {
    const imageSrc = webcamRef.current?.getScreenshot();
    if (imageSrc) {
      const newFace: Face = {
        id: Date.now(),
        imageUrl: imageSrc,
        x: Math.random() * (window.innerWidth - 100),
        y: Math.random() * (window.innerHeight - 100)
      };
      setFaces(prev => [...prev, newFace]);
      setShowWebcam(false);
    }
  }, [webcamRef]);

  const toggleWebcam = () => {
    setShowWebcam(!showWebcam);
  };

  return (
    <Container>
      {faces.map((face) => (
        <Draggable key={face.id}>
          <FloatingFace
            style={{ backgroundImage: `url(${face.imageUrl})` }}
            animate={{
              y: [0, Math.random() * 20 - 10],
              rotate: [0, Math.random() * 10 - 5],
            }}
            transition={{
              duration: 2,
              repeat: Infinity,
              repeatType: "reverse",
              ease: "easeInOut"
            }}
          />
        </Draggable>
      ))}

      <WebcamContainer isVisible={showWebcam}>
        <Webcam
          ref={webcamRef}
          screenshotFormat="image/jpeg"
          width={320}
          height={240}
        />
      </WebcamContainer>

      <CaptureButton onClick={showWebcam ? captureImage : toggleWebcam}>
        {showWebcam ? 'Capture Face' : 'Add New Face'}
      </CaptureButton>
    </Container>
  );
};

export default App; 