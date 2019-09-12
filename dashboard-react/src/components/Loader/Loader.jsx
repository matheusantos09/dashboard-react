import React from 'react'
import LoadingOverlay from 'react-loading-overlay'
import ScaleLoader from 'react-spinners/ScaleLoader'

export default function MyLoader({active, children}) {
    return (
        <LoadingOverlay
            active={active}
            spinner={
                <ScaleLoader
                    height={60}
                    color={'white'}
                />
            }
        >
            {children}
        </LoadingOverlay>
    )
}