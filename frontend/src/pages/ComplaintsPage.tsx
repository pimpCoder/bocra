import { useEffect, useMemo, useRef, useState } from 'react'
import { FiArrowLeft, FiArrowRight } from 'react-icons/fi'
import ComplaintStatusResultSection from '../components/complaints/ComplaintStatusResultSection'
import ComplaintSubmissionSection from '../components/complaints/ComplaintSubmissionSection'
import FeedbackModal from '../components/complaints/FeedbackModal'
import SuccessModal from '../components/complaints/SuccessModal'
import TrackComplaintSection from '../components/complaints/TrackComplaintSection'
import { complaintSections } from '../data/complaints'
import type { ComplaintRecord } from '../types/complaints'

type FlowSection = { id: string; type: 'info' | 'submit' | 'track' | 'status' }

function ComplaintsPage() {
  const [selectedRecord, setSelectedRecord] = useState<ComplaintRecord | null>(null)
  const [isMobile, setIsMobile] = useState<boolean>(false)
  const [activeIndex, setActiveIndex] = useState<number>(0)
  const [resetToken, setResetToken] = useState<number>(0)
  const [isSuccessOpen, setIsSuccessOpen] = useState<boolean>(false)
  const [latestTrackingId, setLatestTrackingId] = useState<string>('')
  const [trackingSeedId, setTrackingSeedId] = useState<string>('')
  const [feedbackModal, setFeedbackModal] = useState<{
    isOpen: boolean
    title: string
    message: string
    tone: 'success' | 'error'
  }>({
    isOpen: false,
    title: '',
    message: '',
    tone: 'success',
  })

  const infoSections = useMemo(() => complaintSections.slice(0, 2), [])
  const sections = useMemo<FlowSection[]>(
    () => [
      ...infoSections.map((section) => ({ id: section.id, type: 'info' as const })),
      { id: 'submit-complaint', type: 'submit' },
      { id: 'track-complaint', type: 'track' },
      ...(selectedRecord ? ([{ id: 'complaint-status', type: 'status' as const }] as FlowSection[]) : []),
    ],
    [infoSections, selectedRecord],
  )

  const submitIndex = sections.findIndex((section) => section.type === 'submit')
  const trackIndex = sections.findIndex((section) => section.type === 'track')

  const containerRef = useRef<HTMLDivElement | null>(null)
  const touchStartRef = useRef<{ x: number; y: number } | null>(null)
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
    setActiveIndex((current) => Math.min(current, totalSections - 1))
  }, [totalSections])

  useEffect(() => {
    const container = containerRef.current
    if (!container) return

    const handleScroll = () => {
      const size = isMobile ? container.clientHeight : container.clientWidth
      const position = isMobile ? container.scrollTop : container.scrollLeft
      const index = Math.round(position / Math.max(size, 1))
      setActiveIndex(Math.min(Math.max(index, 0), totalSections - 1))
    }

    const handleWheel = (event: WheelEvent) => {
      if (isMobile) return
      event.preventDefault()
      container.scrollBy({
        left: event.deltaY + event.deltaX,
        behavior: 'smooth',
      })
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
    container.addEventListener('wheel', handleWheel, { passive: false })
    container.addEventListener('touchstart', handleTouchStart, { passive: true })
    container.addEventListener('touchend', handleTouchEnd, { passive: true })

    return () => {
      container.removeEventListener('scroll', handleScroll)
      container.removeEventListener('wheel', handleWheel)
      container.removeEventListener('touchstart', handleTouchStart)
      container.removeEventListener('touchend', handleTouchEnd)
    }
  }, [activeIndex, isMobile, totalSections])

  const handleSubmitSuccess = (record: ComplaintRecord) => {
    setLatestTrackingId(record.trackingId)
    setTrackingSeedId(record.trackingId)
    setIsSuccessOpen(true)
    window.localStorage.setItem('bocra_last_tracking_id', record.trackingId)
  }

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
        {sections.map((sectionMeta, index) => {
          const bgShade = index % 2 === 0 ? 'bg-slate-100' : 'bg-slate-200/70'
          const infoSection =
            sectionMeta.type === 'info'
              ? infoSections.find((entry) => entry.id === sectionMeta.id) ?? null
              : null

          return (
            <section
              key={sectionMeta.id}
              className={`snap-start ${
                isMobile
                  ? `h-[100svh] w-full overflow-y-auto ${bgShade}`
                  : `h-[100svh] w-screen shrink-0 overflow-y-auto ${bgShade}`
              }`}
            >
              <div className="mx-auto flex min-h-full w-full max-w-6xl items-start px-6 py-14 md:px-10 md:py-16">
                {sectionMeta.type === 'info' && infoSection ? (
                  <div className="w-full py-2 md:py-4">
                    <h1 className="text-balance text-3xl font-extrabold tracking-tight text-slate-900 md:text-5xl">
                      {infoSection.heading}
                    </h1>

                    {infoSection.subheading ? (
                      <h2 className="mt-4 text-xl font-bold text-slate-800 md:text-2xl">
                        {infoSection.subheading}
                      </h2>
                    ) : null}

                    <div className="mt-4 grid gap-4">
                      {infoSection.intro.map((paragraph) => (
                        <p key={paragraph} className="text-base leading-relaxed text-slate-700 md:text-lg">
                          {paragraph}
                        </p>
                      ))}
                    </div>

                    {infoSection.rights?.length ? (
                      <div className="mt-6 grid gap-4">
                        {infoSection.rights.map((right) => (
                          <article key={right.title}>
                            <h3 className="text-lg font-semibold text-slate-900">{right.title}</h3>
                            <p className="mt-2 text-sm leading-relaxed text-slate-700 md:text-base">
                              {right.description}
                            </p>
                          </article>
                        ))}
                      </div>
                    ) : null}

                    {infoSection.steps?.length ? (
                      <div className="mt-6">
                        <h3 className="text-lg font-semibold text-slate-900">The Complaint Handling Process</h3>
                        <ol className="mt-3 grid gap-4">
                          {infoSection.steps.map((step) => (
                            <li key={step.title}>
                              <h4 className="text-base font-semibold text-slate-900">{step.title}</h4>
                              <p className="mt-2 text-sm leading-relaxed text-slate-700 md:text-base">
                                {step.description}
                              </p>
                            </li>
                          ))}
                        </ol>
                      </div>
                    ) : null}

                    {infoSection.listTitle ? (
                      <div className="mt-6">
                        <h3 className="text-lg font-semibold text-slate-900">{infoSection.listTitle}</h3>
                        <ul className="mt-3 grid gap-2 md:ml-5">
                          {infoSection.listItems?.map((item) => (
                            <li key={item} className="list-disc text-sm leading-relaxed text-slate-700 md:text-base">
                              {item}
                            </li>
                          ))}
                        </ul>
                      </div>
                    ) : null}

                    {infoSection.contactTitle ? (
                      <div className="mt-6">
                        <h3 className="text-lg font-semibold text-slate-900">{infoSection.contactTitle}</h3>
                        <address className="mt-3 not-italic text-sm leading-relaxed text-slate-700 md:text-base">
                          {infoSection.contactLines?.map((line) => (
                            <p key={line}>{line}</p>
                          ))}
                        </address>
                      </div>
                    ) : null}

                    {infoSection.notes?.length ? (
                      <div className="mt-6 grid gap-3">
                        {infoSection.notes.map((note) => (
                          <p key={note} className="text-sm leading-relaxed text-slate-700 md:text-base">
                            {note}
                          </p>
                        ))}
                      </div>
                    ) : null}
                  </div>
                ) : null}

                {sectionMeta.type === 'submit' ? (
                  <ComplaintSubmissionSection onSuccess={handleSubmitSuccess} resetToken={resetToken} />
                ) : null}

                {sectionMeta.type === 'track' ? (
                  <TrackComplaintSection
                    initialTrackingId={trackingSeedId}
                    onFound={(record) => {
                      setSelectedRecord(record)
                      setFeedbackModal({
                        isOpen: true,
                        title: 'Complaint Found!',
                        message: `Tracking ID ${record.trackingId} was found. Opening status details now.`,
                        tone: 'success',
                      })
                      window.setTimeout(() => {
                        scrollToSection(totalSections)
                      }, 0)
                    }}
                    onInvalidId={() =>
                      setFeedbackModal({
                        isOpen: true,
                        title: 'Invalid Tracking ID',
                        message: 'Please enter a valid tracking ID before checking status.',
                        tone: 'error',
                      })
                    }
                    onNotFound={(trackingId) =>
                      setFeedbackModal({
                        isOpen: true,
                        title: 'Complaint Not Found',
                        message: `No complaint was found for tracking ID ${trackingId}. Please verify and try again.`,
                        tone: 'error',
                      })
                    }
                  />
                ) : null}

                {sectionMeta.type === 'status' ? (
                  selectedRecord ? (
                    <ComplaintStatusResultSection record={selectedRecord} />
                  ) : (
                    <section className="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                      <h1 className="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">
                        Complaint Status Result
                      </h1>
                      <p className="mt-3 text-sm text-slate-600 md:text-base">
                        No complaint is selected yet. Use the previous section to track a complaint by ID.
                      </p>
                    </section>
                  )
                ) : null}
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

      <SuccessModal
        isOpen={isSuccessOpen}
        onClose={() => setIsSuccessOpen(false)}
        trackingId={latestTrackingId}
        onTrackComplaint={() => {
          setIsSuccessOpen(false)
          scrollToSection(trackIndex)
        }}
        onSubmitAnother={() => {
          setIsSuccessOpen(false)
          setResetToken((current) => current + 1)
          scrollToSection(submitIndex)
        }}
      />

      <FeedbackModal
        isOpen={feedbackModal.isOpen}
        onClose={() => setFeedbackModal((current) => ({ ...current, isOpen: false }))}
        title={feedbackModal.title}
        message={feedbackModal.message}
        tone={feedbackModal.tone}
      />
    </div>
  )
}

export default ComplaintsPage
