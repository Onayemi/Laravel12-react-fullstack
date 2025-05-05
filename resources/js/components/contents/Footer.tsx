import { Link } from '@inertiajs/react';
import { FacebookIcon, GithubIcon, YoutubeIcon } from 'lucide-react';

export default function Footer() {
    return (
        <div className="dark bg-gray-800 py-6 text-gray-200">
            <div className="container mx-auto flex flex-col items-center justify-between px-4 md:flex-row md:px-6">
                <div className="mb-4 text-center md:mb-0 md:text-left">
                    <p className="text-sm">2025 Remlex Technologies. All Rights Reserved</p>
                </div>
                <div className="flex items-center justify-center space-x-4">
                    <Link href="/" className="hover:text-gray text-gray-400 transition-colors">
                        <YoutubeIcon className="h-6 w-6" />
                        <span className="sr-only">Youtube</span>
                    </Link>
                    <Link href="/" className="hover:text-gray text-gray-400 transition-colors">
                        <FacebookIcon className="h-6 w-6" />
                        <span className="sr-only">Facebook</span>
                    </Link>
                    <Link href="/" className="hover:text-gray text-gray-400 transition-colors">
                        <GithubIcon className="h-6 w-6" />
                        <span className="sr-only">Github</span>
                    </Link>
                </div>
            </div>
        </div>
    );
}
