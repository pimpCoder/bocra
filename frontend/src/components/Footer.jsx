import { NavLink } from 'react-router-dom'
import {
  FaFacebookF,
  FaLinkedinIn,
  FaXTwitter,
  FaYoutube,
} from 'react-icons/fa6'
import { HiOutlineMapPin } from 'react-icons/hi2'
import logo from '../assets/logo.svg'

const socialLinks = [
  {
    label: 'Facebook',
    href: 'https://www.facebook.com',
    icon: FaFacebookF,
    color: '#BF1F5A',
  },
  {
    label: 'Twitter',
    href: 'https://x.com',
    icon: FaXTwitter,
    color: '#0A7AA6',
  },
  {
    label: 'YouTube',
    href: 'https://www.youtube.com',
    icon: FaYoutube,
    color: '#038C65',
  },
  {
    label: 'LinkedIn',
    href: 'https://www.linkedin.com',
    icon: FaLinkedinIn,
    color: '#F2B90C',
  },
]

const footerNavLinkClass = ({ isActive }) =>
  `block px-2 py-2 transition-colors duration-200 ${
    isActive ? 'text-[#BF1F5A]' : 'text-white hover:text-[#038C65]'
  }`

function Footer() {
  return (
    <footer>
      <section className="border-t-2 border-white bg-[#A5A7AB] text-white">
        <div className="mx-auto grid w-[min(1200px,94%)] grid-cols-1 gap-12 py-12 md:grid-cols-3">
          <div className="px-6 md:px-8">
            <h2 className="mb-4 text-base font-semibold uppercase tracking-wider text-white">
              Contact
            </h2>
            <div className="space-y-4">
              <img alt="BOCRA logo" className="h-12 w-auto" src={logo} />

              <address
                aria-label="BOCRA contact details"
                className="not-italic text-base leading-7 text-white"
              >
                <div className="space-y-2">
                  <p>Botswana Communications Regulatory Authority</p>

                  <a
                    className="inline-flex items-start gap-2 text-white transition-colors visited:text-[#BF1F5A] hover:text-[#038C65]"
                    href="https://www.google.com/maps/search/?api=1&query=Plot+50671+Independence+Avenue+Gaborone+Botswana"
                    rel="noopener noreferrer"
                    target="_blank"
                  >
                    <HiOutlineMapPin aria-hidden="true" className="h-5 w-5 shrink-0" />
                    <span>
                      Plot 50671 Independence Avenue
                      <br />
                      Gaborone, Botswana
                    </span>
                  </a>

                  <p>Phone: +267 395 7755</p>

                  <a
                    className="text-white transition-colors visited:text-[#BF1F5A] hover:text-[#038C65]"
                    href="mailto:info@bocra.org.bw"
                  >
                    info@bocra.org.bw
                  </a>
                </div>
              </address>
            </div>
          </div>

          <div className="border-t-2 border-white px-6 pt-6 md:border-t-0 md:border-l-2 md:border-white md:px-8 md:pt-0">
            <h2 className="mb-4 text-base font-semibold uppercase tracking-wider text-white">
              Quick Links
            </h2>
            <nav aria-label="Footer navigation">
              <ul className="space-y-2 text-base leading-7 text-white">
                <li>
                  <NavLink className={footerNavLinkClass} end to="/about">
                    About
                  </NavLink>
                </li>
                <li>
                  <NavLink className={footerNavLinkClass} end to="/careers">
                    Careers
                  </NavLink>
                </li>
                <li>
                  <NavLink className={footerNavLinkClass} end to="/licensing">
                    Licensing
                  </NavLink>
                </li>
                <li>
                  <NavLink className={footerNavLinkClass} end to="/complaints">
                    Complaints
                  </NavLink>
                </li>
              </ul>
            </nav>
          </div>

          <div className="border-t-2 border-white px-6 pt-6 md:border-t-0 md:border-l-2 md:border-white md:px-8 md:pt-0">
            <h2 className="mb-4 text-base font-semibold uppercase tracking-wider text-white">
              Connect
            </h2>
            <div className="flex items-center gap-3">
              {socialLinks.map(({ href, icon: Icon, label, color }) => (
                <a
                  key={label}
                  aria-label={label}
                  className="inline-flex h-11 w-11 items-center justify-center rounded-full border-2 border-white/70 bg-white/5 transition-all duration-200 hover:-translate-y-1 hover:scale-105 hover:border-[var(--icon-color)] hover:bg-white/10"
                  href={href}
                  rel="noopener noreferrer"
                  style={{ color, '--icon-color': color }}
                  target="_blank"
                >
                  <Icon className="h-5 w-5" />
                </a>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className="bg-gray-900 text-gray-100">
        <div className="mx-auto flex w-[min(1200px,94%)] flex-col items-start justify-between gap-4 py-3 text-xs sm:flex-row sm:items-center sm:text-sm">
          <p>© 2026 BOCRA. All Rights Reserved.</p>
          <p>Website Design and Development by Binary Mavericks</p>
        </div>
      </section>
    </footer>
  )
}

export default Footer
