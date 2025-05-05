import React from 'react';
import Footer from './Footer';
import Header from './Header';

export default function HeaderProvider({ children }: { children: React.ReactNode }) {
    return (
        <div>
            <Header />
            {children}
            <Footer />
        </div>
    );
}
