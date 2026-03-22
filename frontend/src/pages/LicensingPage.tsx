import { useEffect, useMemo, useRef, useState } from 'react'
import { FiArrowLeft, FiArrowRight } from 'react-icons/fi'

function LicensingPage() {
  const sections = useMemo(
    () => [
      {
        id: 'licensing-framework',
        heading: 'Licensing',
        subheading: 'BOCRA Licensees',
        content: [
          'BOCRA is mandated by Sec 6 (h) of the CRA Act to process applications for and issue, licences, permits, permissions, concessions and authorities for regulated sectors being telecommunications, Internet, radio communications, broadcasting and postal.',
          'Licensing Framework (Telecommunications And Broadcasting)',
          'In 2015, BOCRA commissioned a study to review licensing framework and pricing principles for telecommunications services.  The study culminated with the introduction of a new framework intended to close market gaps that have existed in the previous framework and provide a more conducive environment for market growth and improvement of the welfare of the society taking into account convergence of technologies and evolution to Next Generation Networks.',
          'The framework has primary objective to achieve Efficiency of Convergence where multiple services are delivered on single network or platform embracing convergence of networks, services and technologies. It also aims to achieve Technology neutrality where licensed networks are not distinguished by technology but capability to deliver multiple and multimedia products. The framework further aims to achieve Ease of market entry and increased competition; Consumer choice; Diversification, Open Access as well as Economic Inclusion.',
          'The licensing framework covers broadcasting systems, broadcasting service, subscription management services, electronic communications, telecommunication service and telecommunication systems under broad areas of System license, Service license, Broadcasting and Re-broadcasting Licenses as provided for in the CRA Act.',
          'The framework provides for three major licensing categories being:',
          'Network Facilities Provider (NFP) where licensees own, operate or provide any form of physical infrastructure used principally for carrying service and applications and content. The infrastructure may include fixed links, radio communication transmitters, satellites and satellites station, submarine cable, fibre/copper cable, towers, switches, base stations. The facilities are for own use or for availing to other licensed operators on commercial basis. Private Telecommunications Networks fall in this category and are further specified in the appropriate license type to distinguish them from major networks.',
          'Services And Applications Provider (SAP)',
          'SAPs are non-infrastructure based service providers that provide all forms of services and applications to end users using infrastructure of the Network Facilities Provider. The services and applications may be based on speech, sound, data, text and images and they deliver a specific function to the end user. The services and applications shall not be for broadcasting purposes.',
          'Content Services Provider (CSP)',
          'CSP licensee provides content material in the form of speech or other sounds, text, data, images, whether still or moving solely for broadcasting (TV and radio) and other information services including Subscription TV. NB, State broadcasters do not require license to operate.',
          'Licensing Framework (Postal Services)',
          'In August 2015 BOCRA conducted a study to assess the postal market and develop appropriate licensing framework.  Following conclusion of the study, BOCRA introduced a licensing framework that provides for two licensing categories for the postal sector as follows:',
          'The Designated Postal Operator (DPO) licence.  The licence category provides for a postal operator to be designated to carry universal postal service obligations.',
          'The Commercial Postal Operator (CPO) licence.  The licence category provides for postal operators which provide value-added services.',
        ],
      },
      {
        id: 'section-two',
        heading: 'Section 2',
        subheading: 'Pending Content',
        content: ['Waiting for your instructions for this section.'],
      },
      {
        id: 'section-three',
        heading: 'Section 3',
        subheading: 'Pending Content',
        content: ['Waiting for your instructions for this section.'],
      },
    ],
    []
  )

  const containerRef = useRef<HTMLDivElement | null>(null)
  const touchStartRef = useRef<{ x: number; y: number } | null>(null)
  const [isMobile, setIsMobile] = useState<boolean>(false)
  const [activeIndex, setActiveIndex] = useState<number>(0)
  const totalSections = sections.length

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768)
    handleResize()
    window.addEventListener('resize', handleResize)
    return () => window.removeEventListener('resize', handleResize)
  }, [])

  const scrollToSection = (index: number) => {
    const container = containerRef.current
    if (!container) return

    const nextIndex = Math.min(Math.max(index, 0), totalSections - 1)
    const offset = isMobile
      ? nextIndex * container.clientHeight
      : nextIndex * container.clientWidth

    container.scrollTo({
      top: isMobile ? offset : 0,
      left: isMobile ? 0 : offset,
      behavior: 'smooth',
    })
  }

  useEffect(() => {
    const container = containerRef.current
    if (!container) return

    const handleScroll = () => {
      const size = isMobile ? container.clientHeight : container.clientWidth
      const position = isMobile ? container.scrollTop : container.scrollLeft
      const index = Math.round(position / Math.max(size, 1))
      setActiveIndex(Math.min(Math.max(index, 0), totalSections - 1))
    }

    const handleTouchStart = (event: TouchEvent) => {
      const touch = event.touches[0]
      touchStartRef.current = { x: touch.clientX, y: touch.clientY }
    }

    const handleTouchEnd = (event: TouchEvent) => {
      if (!touchStartRef.current) return

      const touch = event.changedTouches[0]
      const deltaX = touchStartRef.current.x - touch.clientX
      const deltaY = touchStartRef.current.y - touch.clientY
      const threshold = 40

      if (isMobile) {
        if (Math.abs(deltaY) > threshold && Math.abs(deltaY) > Math.abs(deltaX)) {
          scrollToSection(activeIndex + (deltaY > 0 ? 1 : -1))
        }
      } else if (Math.abs(deltaX) > threshold && Math.abs(deltaX) > Math.abs(deltaY)) {
        scrollToSection(activeIndex + (deltaX > 0 ? 1 : -1))
      }

      touchStartRef.current = null
    }

    handleScroll()
    container.addEventListener('scroll', handleScroll, { passive: true })
    container.addEventListener('touchstart', handleTouchStart, { passive: true })
    container.addEventListener('touchend', handleTouchEnd, { passive: true })

    return () => {
      container.removeEventListener('scroll', handleScroll)
      container.removeEventListener('touchstart', handleTouchStart)
      container.removeEventListener('touchend', handleTouchEnd)
    }
  }, [activeIndex, isMobile, totalSections])

  return (
    <div className="relative">
      <div
        ref={containerRef}
        className={`w-screen ml-[calc(50%-50vw)] mr-[calc(50%-50vw)] ${
          isMobile
            ? 'h-[100svh] overflow-y-auto overflow-x-hidden snap-y snap-mandatory'
            : 'h-[100svh] overflow-x-auto overflow-y-hidden snap-x snap-mandatory flex'
        } scroll-smooth [scrollbar-width:none] [&::-webkit-scrollbar]:hidden`}
      >
        {sections.map((section, index) => {
          const bgShade = index % 2 === 0 ? 'bg-slate-100' : 'bg-slate-200/70'

          return (
            <section
              key={section.id}
              id={section.id === 'section-three' ? 'apply' : undefined}
              className={`snap-start ${
                isMobile
                  ? `h-[100svh] w-full ${bgShade}`
                  : `h-[100svh] w-screen shrink-0 ${bgShade}`
              }`}
            >
              <div
                className={`mx-auto flex h-full w-full max-w-6xl items-start px-6 py-12 md:px-10 md:py-14 ${
                  activeIndex === index ? 'opacity-100 translate-y-0' : 'opacity-95 translate-y-2'
                } transition-all duration-500`}
              >
                <div className="w-full md:max-h-[72svh] md:overflow-y-auto md:pr-2">
                  <h1 className="text-balance text-3xl font-extrabold tracking-tight text-slate-900 md:text-5xl">
                    {section.heading}
                  </h1>
                  <h2 className="mt-3 text-lg font-semibold text-slate-800 md:text-2xl">
                    {section.subheading}
                  </h2>

                  <div className="mt-5 max-w-5xl space-y-4 text-sm leading-relaxed text-slate-700 md:text-base">
                    {section.content.map((paragraph) => {
                      const isSubsectionTitle =
                        paragraph === 'Licensing Framework (Telecommunications And Broadcasting)' ||
                        paragraph === 'Licensing Framework (Postal Services)' ||
                        paragraph === 'Services And Applications Provider (SAP)' ||
                        paragraph === 'Content Services Provider (CSP)'

                      return isSubsectionTitle ? (
                        <h3 key={paragraph} className="pt-2 text-base font-semibold text-slate-900 md:text-lg">
                          {paragraph}
                        </h3>
                      ) : (
                        <p key={paragraph}>{paragraph}</p>
                      )
                    })}
                  </div>
                </div>
              </div>
            </section>
          )
        })}
      </div>

      {!isMobile ? (
        <div className="pointer-events-none fixed inset-x-0 top-1/2 z-30 mx-auto flex w-full max-w-[98vw] -translate-y-1/2 justify-between px-3">
          <button
            type="button"
            onClick={() => scrollToSection(activeIndex - 1)}
            className="pointer-events-auto inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-300 bg-white/95 text-slate-700 shadow-sm transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-45"
            disabled={activeIndex === 0}
            aria-label="Go to previous section"
          >
            <FiArrowLeft />
          </button>
          <button
            type="button"
            onClick={() => scrollToSection(activeIndex + 1)}
            className="pointer-events-auto inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-300 bg-white/95 text-slate-700 shadow-sm transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-45"
            disabled={activeIndex === totalSections - 1}
            aria-label="Go to next section"
          >
            <FiArrowRight />
          </button>
        </div>
      ) : null}

      <div className="fixed bottom-5 left-1/2 z-40 -translate-x-1/2 rounded-full border border-slate-300/80 bg-white/90 px-4 py-2 shadow-lg backdrop-blur">
        <div className="flex items-center gap-3">
          <div className="flex items-center gap-2">
            {sections.map((section, index) => (
              <button
                key={section.id}
                type="button"
                onClick={() => scrollToSection(index)}
                className={`h-2.5 w-2.5 rounded-full transition ${
                  activeIndex === index ? 'bg-slate-900 scale-110' : 'bg-slate-400 hover:bg-slate-500'
                }`}
                aria-label={`Go to section ${index + 1}`}
              />
            ))}
          </div>
          <p className="text-xs font-semibold tracking-wide text-slate-700">
            {activeIndex + 1}/{totalSections}
          </p>
        </div>
      </div>
    </div>
  )
}

export default LicensingPage
